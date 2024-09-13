<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatbotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('chatbot.index');
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
