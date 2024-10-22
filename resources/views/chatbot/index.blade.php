@extends('layouts.patient.patient-layout')

@section('content')
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>MySihat Chatbot</title>
        <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    </head>

    <body>
        <div class="chat-container">
            <div class="chat-messages" id="chatMessages">
                @include('chatbot.partials.chatbubble', [
                    'message' => 'Welcome to MySihat, I am MySihat Bot. How can I assist you today?',
                    'className' => 'claude-message',
                    'sender' => 'MySihat Bot',
                    'avatarUrl' => 'https://img.freepik.com/premium-vector/medical-robot-android_111928-2.jpg',
                ])
            </div>
            <div id="summary-container">
                <div class="summary-title">Summary</div>
                <div class="summary-content" id="summaryContent"></div>
            </div>
            <div class="input-area">
                <input type="text" id="messageInput" class="form-control" placeholder="Type your message...">
                <button class="btn" type="button" id="sendButton">
                    <i class="fas fa-arrow-up"></i>
                </button>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/chat.js') }}"></script>
    </body>
@endsection

