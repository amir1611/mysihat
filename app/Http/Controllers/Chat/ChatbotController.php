<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Parsedown;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $greeting = "Hi {$user->name}. Welcome to MySihat, I am MySihat Bot. How can I assist you today?";
        
        return view('chatbot.index', compact('greeting'));
    }

    public function render(Request $request) {
        return view('chatbot.partials.chatbubble', [
            'message' => $request->input('message'),
            'className' => $request->input('className'),
            'sender' => $request->input('sender'),
            'avatarUrl' => $request->input('avatarUrl'),
            'id' => $request->input('id')
        ]);
    }
}
