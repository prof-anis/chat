<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function chat()
    {
   // dd(Chat::with('sender')->get());
        return Inertia::render('ChatComponent',[
            'user' => Auth::user(),
            'chats' => Chat::with('sender')->orderBy('created_at', 'DESC')->get()
        ]);
    }

    public function store(Request $request)
    {
        $chat = Chat::create($request->only('sender_id', 'message'));
        $chat = Chat::with('sender')->where('id', $chat->id)->first();
        broadcast(new MessageSent($chat))->toOthers();
        return response()->json(['chat' => $chat]);
    }
}
