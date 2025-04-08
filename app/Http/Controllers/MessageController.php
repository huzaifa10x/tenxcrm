<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return view('messages.index', compact('users'));
    }

    public function fetchMessages($userId)
    {
        $messages = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', auth()->user()->id)
                ->where('receiver_id', $userId)
                ->where('deleted_by_sender', false);
        })->orWhere(function ($q) use ($userId) {
            $q->where('receiver_id', auth()->user()->id)
                ->where('sender_id', $userId)
                ->where('deleted_by_receiver', false);
        })->orderBy('created_at', 'asc')->get();

        $messages = $messages->map(function ($message) {
            $sender = User::find($message->sender_id);
            $message->sender_img = $sender && $sender->img
                ? asset('storage/' . $sender->img)
                : asset('images/avatar/avatar1.png');
            $message->sender_img = $sender->img ? asset('/storage/app/public/' . $sender->img) : asset('images/avatar/avatar1.png');
            $message->created_at_formatted = Carbon::parse($message->created_at)->format('d M, Y h:i A');
            return $message;
        });

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        DB::table('notifications')->insert([
            'id' => Str::uuid(),
            'type' => \App\Notifications\CustomNotification::class,
            'notifiable_type' => \App\Models\User::class,
            'notifiable_id' => $request->receiver_id,
            'data' => json_encode([
                'title' => auth()->user()->name . " Sent you a message",
                'message' => $request->message,
                'url' => route('messages.index'),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json($message);
    }

    public function deleteForMe(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id == auth()->user()->id) {
            $message->update(['deleted_by_sender' => true]);
        } elseif ($message->receiver_id == auth()->user()->id) {
            $message->update(['deleted_by_receiver' => true]);
        }

        return response()->json(['status' => 'deleted']);
    }
    public function deleteForEveryone($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id == auth()->user()->id) {
            $message->delete();
            return response()->json(['status' => 'deleted for everyone']);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
    public function getUsersWithUnreadMessages()
    {
        $users = User::all()->map(function ($user) {
            $unreadCount = Message::where('receiver_id', auth()->id())
                ->where('sender_id', $user->id)
                ->where('is_read', false)
                ->count();

            $user->unread_count = $unreadCount;
            return $user;
        });

        return response()->json($users);
    }
    public function markMessagesAsRead($userId)
    {
        Message::where('receiver_id', auth()->user()->id)
            ->where('sender_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }
}
