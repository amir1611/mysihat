// skipcq: JS-0241
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#sendButton").click(function () {
        sendMessage();
    });

    $("#messageInput").keypress(function (e) {
        if (e.which == 13) {
            sendMessage();
            return false;
        }
    });

    function sendMessage() {
        var message = $("#messageInput").val();
        if (message.trim() === "") return;

        $.post(
            "/render-message",
            {
                message: message,
                className: "user-message",
                sender: "You",
                avatarUrl: "https://ui-avatars.com/api/?name=Nurul&background=random&color=ffffff",
            },
            function (data) {
                $("#chatMessages").append(data);
                scrollToBottom();

                getAIResponse(message);
            }
        );

        $("#messageInput").val("");
    }

    function getAIResponse(message) {
        const queryQuestion = encodeURIComponent(message);
        let url = `/chat/streaming?question=${queryQuestion}`;
        const source = new EventSource(url);

        // Create a new message bubble for the AI response with loading state
        let responseBubbleId = "ai-response-" + Date.now();
        $.post(
            "/render-message",
            {
                message: '<div class="loading-dots">...</div>',
                className: "claude-message",
                sender: "Dr. AI",
                avatarUrl: "https://ui-avatars.com/api/?name=Dr. AI&background=random&color=ffffff",
                id: responseBubbleId,
            },
            function (data) {
                $("#chatMessages").append(data);
                scrollToBottom();
            }
        );

        let isFirstUpdate = true;

        source.addEventListener("update", (event) => {
            if (event.data === "<END_STREAMING_SSE>") {
                source.close();
                return;
            }

            const data = JSON.parse(event.data);
            if (data.text) {
                if (isFirstUpdate) {
                    // Remove loading state on first update
                    clearMessageContent(responseBubbleId);
                    isFirstUpdate = false;
                }
                updateMessage(responseBubbleId, data.text);
            }
        });

        source.addEventListener("error", (event) => {
            source.close();
            console.log("EventSource Failed", event);
            clearMessageContent(responseBubbleId);
            updateMessage(
                responseBubbleId,
                "Sorry, I couldn't process your request."
            );
        });
    }

    function updateMessage(id, text) {
        var $messageContent = $("#" + id + " .message-content");
        $messageContent.append(marked.parse(text));
        scrollToBottom();
    }

    function clearMessageContent(id) {
        var $messageContent = $("#" + id + " .message-content");
        $messageContent.empty();
    }

    function scrollToBottom() {
        $("#chatMessages").scrollTop($("#chatMessages")[0].scrollHeight);
    }
});
