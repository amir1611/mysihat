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
                    'message' => $greeting,
                    'className' => 'claude-message',
                    'sender' => 'MySihat Bot',
                    'avatarUrl' => '/build/assets/mysihatbot.png',
                ])
            </div>
            <details id="summary-container">
                <summary class="summary-title">Summary</summary>
                <div class="summary-content" id="summaryContent"></div>
            </details>
            <div class="input-area">
                <input type="text" id="messageInput" class="form-control" placeholder="Message MySihat Bot...">
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
