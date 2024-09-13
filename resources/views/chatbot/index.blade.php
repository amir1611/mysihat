@extends('layouts.patient-layout')

@section('content')

    <head>
        <style>
            body {
                background-color: #ffffff;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }

            .chat-container {
                max-width: 900px;
                margin: 0 auto;
                padding: 20px;
                display: flex;
                flex-direction: column;
                height: 100vh;
            }

            .chat-messages {
                flex-grow: 1;
                overflow-y: auto;
                padding: 1rem;
                background-color: #ffffff;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                margin-bottom: 20px;
            }

            .message {
                margin-bottom: 1.5rem;
                line-height: 1.5;
            }

            .message-content {
                padding: 0.5rem 1rem;
                border-radius: 8px;
                max-width: 80%;
            }

            .user-message .message-content {
                background-color: #f0f8ff;
                color: #000000;
                margin-left: auto;
            }

            .claude-message .message-content {
                background-color: #f8f8f8;
                color: #000000;
            }

            .message-sender {
                font-weight: bold;
                margin-bottom: 0.25rem;
            }

            .user-message .message-sender {
                text-align: right;
            }

            .input-area {
                background-color: #ffffff;
                padding: 1rem;
                position: sticky;
                bottom: 0;
                border-radius: 8px;
                display: flex;
                justify-content: space-between;
                gap: 0.5rem;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            #messageInput {
                flex-grow: 1;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 0.5rem 1rem;
            }

            #sendButton {
                background-color: #10a37f;
                border: none;
                cursor: pointer;
                padding: 0.5rem 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
            }

            #sendButton i {
                color: #ffffff;
                font-size: 14px;
            }

            #sendButton:hover {
                background-color: #0d8267;
            }

            #sendButton:focus {
                outline: none;
            }
        </style>
    </head>

    <body>
        <div class="chat-container">
            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be appended here -->
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
        <script>
            $(document).ready(function() {
                appendMessage('Welcome to MySihat, I am Dr.AI. How can I assist you today?', 'claude-message',
                'Dr. AI');

                $('#sendButton').click(function() {
                    sendMessage();
                });

                $('#messageInput').keypress(function(e) {
                    if (e.which == 13) {
                        sendMessage();
                        return false;
                    }
                });

                function sendMessage() {
                    var message = $('#messageInput').val();
                    if (message.trim() === '') return;

                    appendMessage(message, 'user-message', 'You');

                    const queryQuestion = encodeURIComponent(message);

                    let url = `/chat/streaming?question=${queryQuestion}`;
                    const source = new EventSource(url);

                    let responseBubbleId = 'ai-response-' + Date.now();
                    appendMessage('', 'claude-message', 'Dr. AI', responseBubbleId);

                    source.addEventListener('update', (event) => {
                        if (event.data === "<END_STREAMING_SSE>") {
                            source.close();
                            return;
                        }

                        const data = JSON.parse(event.data);
                        if (data.text) {
                            updateMessage(responseBubbleId, data.text);
                        }
                    });

                    source.addEventListener('error', (event) => {
                        source.close();
                        console.log('EventSource Failed', event);
                        updateMessage(responseBubbleId, "Sorry, I couldn't process your request.");
                    });

                    $('#messageInput').val('');
                }

                function appendMessage(message, className, sender, id = null) {
                    var messageHtml = '<div class="message ' + className + '"' + (id ? ' id="' + id + '"' : '') + '>' +
                        '<div class="message-sender">' + sender + '</div>' +
                        '<div class="message-content">' + message + '</div>' +
                        '</div>';
                    $('#chatMessages').append(messageHtml);
                    $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
                }

                function updateMessage(id, text) {
                    var $messageContent = $('#' + id + ' .message-content');
                    $messageContent.append(text);
                    $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
                }
            });
        </script>
    </body>
@endsection
