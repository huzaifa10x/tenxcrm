<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Support\InAppNotification;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TaskCommentController extends Controller
{
    private const MAX_LENGTH = 10000;

    /** Oldest first, so the thread reads top to bottom like a chat. */
    public function index($task_id)
    {
        $comments = TaskComment::where('task_id', $task_id)
            ->with(['user:id,name,img', 'replyTo.user:id,name'])
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return response()->json([
            'comments' => $comments->map(fn($comment) => $this->present($comment))->values(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_id'      => 'required|integer|exists:tasks,id',
            'comment'      => 'required|string|max:' . self::MAX_LENGTH,
            'reply_to_id'  => 'nullable|integer',
            'client_token' => 'nullable|string|max:64',
        ]);

        $body = $this->sanitize($request->input('comment'));
        if ($this->isBlank($body)) {
            return response()->json(['message' => 'Comment cannot be empty.'], 422);
        }

        // A reply must point at a comment on the same task.
        $replyToId = null;
        if ($request->filled('reply_to_id')) {
            $replyToId = TaskComment::where('id', $request->input('reply_to_id'))
                ->where('task_id', $request->input('task_id'))
                ->value('id');
        }

        // Idempotency: a retried or double-sent request with the same token returns the
        // comment that was already saved instead of creating a second one.
        $tokenKey = $request->filled('client_token')
            ? 'task-comment:' . auth()->id() . ':' . $request->input('client_token')
            : null;

        $lockKey = null;
        if ($tokenKey) {
            if ($existingId = Cache::get($tokenKey)) {
                if ($existing = TaskComment::with(['user:id,name,img', 'replyTo.user:id,name'])->find($existingId)) {
                    return response()->json(['success' => true, 'comment' => $this->present($existing)]);
                }
            }
            $lockKey = $tokenKey . ':lock';
            if (!Cache::add($lockKey, 1, 15)) {
                return response()->json(['message' => 'This comment is already being sent.'], 409);
            }
        }

        try {
            $comment = TaskComment::create([
                'task_id'     => $request->input('task_id'),
                'user_id'     => auth()->id(),
                'reply_to_id' => $replyToId,
                'comment'     => $body,
            ]);

            if ($tokenKey) {
                Cache::put($tokenKey, $comment->id, now()->addMinutes(30));
            }
        } finally {
            if ($lockKey) {
                Cache::forget($lockKey);
            }
        }

        // The comment is saved; a notification problem must never turn this into an error
        // (that is what made users resend and post duplicates before).
        try {
            $this->notify($comment);
        } catch (\Throwable $e) {
            Log::error('Task comment notification failed', ['comment_id' => $comment->id, 'error' => $e->getMessage()]);
        }

        $comment->load(['user:id,name,img', 'replyTo.user:id,name']);

        return response()->json(['success' => true, 'comment' => $this->present($comment)], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|max:' . self::MAX_LENGTH]);

        $comment = TaskComment::findOrFail($id);
        if ($comment->user_id != auth()->id()) {
            return response()->json(['message' => 'You can only edit your own comments.'], 403);
        }

        $body = $this->sanitize($request->input('comment'));
        if ($this->isBlank($body)) {
            return response()->json(['message' => 'Comment cannot be empty.'], 422);
        }

        $comment->comment = $body;
        // Timestamps have one-second precision: make sure a quick edit still reads as edited.
        $comment->updated_at = now()->max($comment->created_at->copy()->addSecond());
        $comment->save();
        $comment->load(['user:id,name,img', 'replyTo.user:id,name']);

        return response()->json(['success' => true, 'comment' => $this->present($comment)]);
    }

    public function destroy($id)
    {
        $comment = TaskComment::findOrFail($id);
        if ($comment->user_id != auth()->id()) {
            return response()->json(['message' => 'You can only delete your own comments.'], 403);
        }

        // Replies stay; they just lose the quote (the FK does this too, this covers databases without it).
        TaskComment::where('reply_to_id', $comment->id)->update(['reply_to_id' => null]);
        $comment->delete();

        return response()->json(['success' => true]);
    }

    /* ───────────────────────── Helpers ───────────────────────── */

    private function present(TaskComment $comment): array
    {
        $reply = $comment->replyTo;

        return [
            'id'         => $comment->id,
            'user_id'    => $comment->user_id,
            'user'       => [
                'id'   => $comment->user->id ?? null,
                'name' => $comment->user->name ?? 'Deleted user',
                'img'  => $comment->user->img ?? null,
            ],
            'body'       => $comment->comment,
            'text'       => $this->toPlainText($comment->comment),
            'created_at' => optional($comment->created_at)->toIso8601String(),
            'updated_at' => optional($comment->updated_at)->toIso8601String(),
            'edited'     => $comment->created_at && $comment->updated_at
                && $comment->updated_at->gt($comment->created_at),
            'is_mine'    => (int) $comment->user_id === (int) auth()->id(),
            'reply_to'   => $reply ? [
                'id'        => $reply->id,
                'user_name' => $reply->user->name ?? 'Deleted user',
                'excerpt'   => Str::limit($this->toPlainText($reply->comment), 140),
            ] : null,
        ];
    }

    /**
     * The composer sends plain text: everything typed is escaped (so "<b>" or code snippets
     * show exactly as written), URLs become links and line breaks are kept. The result is
     * still passed through HTMLPurifier as a final safety net. Comments saved earlier by the
     * old rich-text editor stay as they are in the database and keep their formatting.
     */
    private function sanitize(?string $input): string
    {
        $escaped = e(trim(str_replace("\r\n", "\n", (string) $input)));
        $linked = preg_replace(
            '~https?://(?:(?!&lt;|&gt;|&quot;|&#039;)[^\s<])+(?<![.,;:!?)\]])~i',
            '<a href="$0" target="_blank" rel="noopener noreferrer">$0</a>',
            $escaped
        );

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Attr.AllowedFrameTargets', ['_blank']);

        return trim((new HTMLPurifier($config))->purify(nl2br($linked, false)));
    }

    private function isBlank(string $html): bool
    {
        return trim(str_replace("\xC2\xA0", ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'))) === '';
    }

    private function toPlainText(?string $html): string
    {
        $text = preg_replace('~<br\s*/?>\n?~i', "\n", (string) $html);
        $text = preg_replace('~</(p|div|li|h[1-6])>~i', "\n", $text);
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\xC2\xA0", ' ', $text);

        return trim(preg_replace("~\n{3,}~", "\n\n", $text));
    }

    /** Notify the assignee and, for a reply, the author being replied to - never the commenter. */
    private function notify(TaskComment $comment): void
    {
        $task = Task::find($comment->task_id);
        if (!$task) {
            return;
        }

        $author = auth()->user();
        $url = url("tasks/{$task->id}");
        $notified = [(int) $author->id];

        if ($task->assigned_to && !in_array((int) $task->assigned_to, $notified, true)) {
            if ($assignee = User::find($task->assigned_to)) {
                InAppNotification::send(
                    $assignee,
                    $author->name . ' commented on ' . $task->title,
                    $author->name . ' has commented on ' . $task->title,
                    $url
                );
                $notified[] = (int) $assignee->id;
            }
        }

        $replyAuthorId = $comment->reply_to_id ? (int) TaskComment::where('id', $comment->reply_to_id)->value('user_id') : 0;
        if ($replyAuthorId && !in_array($replyAuthorId, $notified, true)) {
            if ($replyAuthor = User::find($replyAuthorId)) {
                InAppNotification::send(
                    $replyAuthor,
                    $author->name . ' replied to your comment',
                    $author->name . ' replied to your comment on ' . $task->title,
                    $url
                );
            }
        }
    }
}
