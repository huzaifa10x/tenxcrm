<?php

namespace Tests\Feature;

use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TaskCommentsTest extends TestCase
{
    use DatabaseTransactions;

    private User $author;
    private User $assignee;
    private int $taskId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = $this->makeUser('Author One', 'author@example.test');
        $this->assignee = $this->makeUser('Assignee Two', 'assignee@example.test');

        $companyId = $this->row('companies', ['name' => 'Acme', 'created_at' => now(), 'updated_at' => now()]);
        $projectId = $this->row('projects', [
            'title' => 'Acme SEO', 'description' => 'd', 'category' => 'GOLD', 'company_id' => $companyId,
            'status' => 'Current', 'contract_start_date' => now()->toDateString(), 'contract_term' => 12,
            'contract_value' => 1200, 'free_months' => 0, 'created_by' => $this->author->id,
            'handover_notes' => '', 'project_type' => 'SEO', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->taskId = $this->row('tasks', [
            'title' => 'Write landing page', 'project_id' => $projectId, 'created_by' => $this->author->id,
            'assigned_to' => $this->assignee->id, 'priority' => 'Medium', 'type' => 'ADHOC', 'status' => 'Todo',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    /** Insert a row, filling any required column the fixture didn't set with a harmless value. */
    private function row(string $table, array $data): int
    {
        $cols = DB::select('SELECT COLUMN_NAME c, DATA_TYPE t, COLUMN_TYPE ct FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND IS_NULLABLE = "NO" AND COLUMN_DEFAULT IS NULL AND EXTRA NOT LIKE "%auto_increment%"', [$table]);
        foreach ($cols as $col) {
            if (array_key_exists($col->c, $data)) continue;
            $data[$col->c] = match (true) {
                $col->t === 'enum' => trim(explode(',', substr($col->ct, 5, -1))[0], "'"),
                in_array($col->t, ['int', 'bigint', 'tinyint', 'smallint', 'decimal', 'double', 'float']) => 0,
                in_array($col->t, ['date']) => now()->toDateString(),
                in_array($col->t, ['datetime', 'timestamp']) => now(),
                default => '',
            };
        }

        return DB::table($table)->insertGetId($data);
    }

    private function makeUser(string $name, string $email): User
    {
        $user = new User();
        $user->forceFill([
            'name' => $name, 'email' => $email, 'password' => bcrypt('secret'),
            'email_verified_at' => now(),
        ])->save();

        return $user;
    }

    private function postComment(array $data = [])
    {
        return $this->actingAs($this->author)->postJson(route('comments.store'), array_merge([
            'task_id' => $this->taskId,
            'comment' => "First line\nSecond line https://example.com/page <script>alert(1)</script>",
        ], $data));
    }

    public function test_store_returns_presented_comment_and_sanitizes(): void
    {
        $res = $this->postComment()->assertCreated()->assertJsonPath('success', true);

        $body = $res->json('comment.body');
        $this->assertStringContainsString('First line<br', $body);
        $this->assertStringContainsString('<a href="https://example.com/page"', $body);
        $this->assertStringNotContainsString('<script>', $body);
        $this->assertSame("First line\nSecond line https://example.com/page <script>alert(1)</script>", $res->json('comment.text'));
        $this->assertTrue($res->json('comment.is_mine'));
        $this->assertSame('Author One', $res->json('comment.user.name'));
        $this->assertArrayNotHasKey('email', $res->json('comment.user'));
    }

    public function test_assignee_is_notified_but_not_the_author(): void
    {
        $this->postComment()->assertCreated();

        $this->assertSame(1, DB::table('notifications')->where('notifiable_id', $this->assignee->id)->count());
        $this->assertSame(0, DB::table('notifications')->where('notifiable_id', $this->author->id)->count());
    }

    public function test_task_without_assignee_still_saves_without_error(): void
    {
        DB::table('tasks')->where('id', $this->taskId)->update(['assigned_to' => null]);

        $this->postComment()->assertCreated();
        $this->assertSame(1, TaskComment::where('task_id', $this->taskId)->count());
    }

    public function test_same_client_token_never_creates_a_duplicate(): void
    {
        $first = $this->postComment(['client_token' => 'abc-123'])->assertCreated()->json('comment.id');
        $second = $this->postComment(['client_token' => 'abc-123'])->assertOk()->json('comment.id');

        $this->assertSame($first, $second);
        $this->assertSame(1, TaskComment::where('task_id', $this->taskId)->count());
    }

    public function test_blank_and_whitespace_comments_are_rejected(): void
    {
        $this->postComment(['comment' => ''])->assertStatus(422);
        $this->postComment(['comment' => "   \n  "])->assertStatus(422);
        $this->postComment(['comment' => "\u{00A0}\t"])->assertStatus(422);
        $this->assertSame(0, TaskComment::count());
    }

    public function test_reply_is_linked_notifies_original_author_and_cannot_cross_tasks(): void
    {
        $parent = $this->actingAs($this->assignee)->postJson(route('comments.store'), [
            'task_id' => $this->taskId, 'comment' => 'Can you check the hero copy?',
        ])->json('comment.id');

        $third = $this->makeUser('Third Person', 'third@example.test');
        $res = $this->actingAs($third)->postJson(route('comments.store'), [
            'task_id' => $this->taskId, 'comment' => 'Checked, looks good', 'reply_to_id' => $parent,
        ])->assertCreated();

        $this->assertSame($parent, $res->json('comment.reply_to.id'));
        $this->assertSame('Assignee Two', $res->json('comment.reply_to.user_name'));
        $this->assertSame('Can you check the hero copy?', $res->json('comment.reply_to.excerpt'));
        // Assignee gets one notification (as assignee); the reply does not double-notify them.
        $this->assertSame(1, DB::table('notifications')->where('notifiable_id', $this->assignee->id)->count());

        $otherTask = $this->row('tasks', [
            'title' => 'Other', 'project_id' => DB::table('tasks')->where('id', $this->taskId)->value('project_id'),
            'created_by' => $this->author->id, 'type' => 'ADHOC', 'status' => 'Todo', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $cross = $this->actingAs($third)->postJson(route('comments.store'), [
            'task_id' => $otherTask, 'comment' => 'x', 'reply_to_id' => $parent,
        ])->assertCreated();
        $this->assertNull($cross->json('comment.reply_to'));
    }

    public function test_index_is_oldest_first_and_marks_ownership(): void
    {
        $a = $this->postComment(['comment' => 'one'])->json('comment.id');
        $this->travel(2)->minutes();
        $b = $this->actingAs($this->assignee)->postJson(route('comments.store'), ['task_id' => $this->taskId, 'comment' => 'two'])->json('comment.id');

        $res = $this->actingAs($this->author)->getJson(route('comments.index', $this->taskId))->assertOk();
        $this->assertSame([$a, $b], array_column($res->json('comments'), 'id'));
        $this->assertSame([true, false], array_column($res->json('comments'), 'is_mine'));
    }

    public function test_only_the_author_can_edit_or_delete_and_edit_is_sanitized(): void
    {
        $id = $this->postComment(['comment' => 'original'])->json('comment.id');

        $this->actingAs($this->assignee)->putJson(route('comments.update', $id), ['comment' => 'hijack'])->assertForbidden();
        $this->actingAs($this->assignee)->deleteJson(route('comments.destroy', $id))->assertForbidden();

        $this->travel(1)->minutes();
        $res = $this->actingAs($this->author)->putJson(route('comments.update', $id), ['comment' => 'fixed <img src=x onerror=alert(1)>'])->assertOk();
        $this->assertStringNotContainsString('<img', $res->json('comment.body'));
        $this->assertTrue($res->json('comment.edited'));

        $this->actingAs($this->author)->deleteJson(route('comments.destroy', $id))->assertOk();
        $this->assertNull(TaskComment::find($id));
    }

    public function test_deleting_a_parent_keeps_the_reply(): void
    {
        $parent = $this->postComment(['comment' => 'parent'])->json('comment.id');
        $reply = $this->postComment(['comment' => 'child', 'reply_to_id' => $parent])->json('comment.id');

        $this->actingAs($this->author)->deleteJson(route('comments.destroy', $parent))->assertOk();

        $this->assertNotNull(TaskComment::find($reply));
        $this->assertNull(TaskComment::find($reply)->reply_to_id);
    }

    public function test_urls_are_linked_without_swallowing_neighbours(): void
    {
        $body = $this->postComment(['comment' => 'See (https://ex.com/a?x=1&y=2). Or <https://ex.com/b>'])->json('comment.body');
        $this->assertStringContainsString('<a href="https://ex.com/a?x=1&amp;y=2" target="_blank" rel="noreferrer noopener">', $body);
        $this->assertStringContainsString('<a href="https://ex.com/b"', $body);
    }

    public function test_typed_markup_is_shown_as_text_not_executed(): void
    {
        $res = $this->postComment(['comment' => 'Use <b>bold</b> & keep a < b'])->assertCreated();
        $this->assertStringContainsString('&lt;b&gt;bold&lt;/b&gt;', $res->json('comment.body'));
        $this->assertSame('Use <b>bold</b> & keep a < b', $res->json('comment.text'));
    }

    public function test_legacy_html_comments_still_render_with_formatting(): void
    {
        $id = DB::table('task_comments')->insertGetId([
            'task_id' => $this->taskId, 'user_id' => $this->assignee->id,
            'comment' => '<p><b>Bold</b> legacy</p><ul><li>item</li></ul>', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $c = collect($this->actingAs($this->author)->getJson(route('comments.index', $this->taskId))->json('comments'))->firstWhere('id', $id);
        $this->assertStringContainsString('<b>Bold</b>', $c['body']);
        $this->assertSame("Bold legacy\nitem", $c['text']);
    }
}
