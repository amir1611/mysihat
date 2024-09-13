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
}
