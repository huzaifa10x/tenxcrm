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
        $users = User::where('id', '!=', auth()->id())->get();
        return view('messages.index', compact('users'));
    }

    public function fetchMessages($userId)
    {
        $messages = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        // Append user image to each message
        $messages = $messages->map(function ($message) {
            $sender = User::find($message->sender_id);
            $message->sender_img = $sender->img ? asset('/storage/app/public/' . $sender->img) : asset('images/avatar/avatar1.png');
            $message->sender_name = $sender->name;
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
            'sender_id' => auth()->id(),
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
                'message' => auth()->user()->name . " sent you " . $request->message,
                'url' => route('messages.index'),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json($message);
    }
}
