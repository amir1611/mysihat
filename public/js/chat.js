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

        addMessage(message, "user-message", "You", "https://ui-avatars.com/api/?name=Nurul&background=random&color=ffffff");

        getAIResponse(message);

        $("#messageInput").val("");
    }

    function getAIResponse(message) {
        const queryQuestion = encodeURIComponent(message);
        let url = `/chat/streaming?question=${queryQuestion}`;
        const source = new EventSource(url);

        let responseBubbleId = "ai-response-" + Date.now();
        addMessage('<div class="loading-dots">...</div>', "claude-message", "MySihat Bot", "/build/assets/mysihatbot.png", responseBubbleId);

        let isFirstUpdate = true;
        let fullResponse = '';

        source.addEventListener("update", (event) => {
            if (event.data === "<END_STREAMING_SSE>") {
                source.close();

                let appointmentPromptId = "appointment-prompt-" + Date.now();
                addMessage('Would you like to book an online appointment with a medical professional?', "claude-message", "MySihat Bot", "/build/assets/mysihatbot.png", appointmentPromptId);

                let buttonsHtml = `
                    <div id="appointment-buttons" style="margin-top: 10px; text-align: center;">
                        <button id="yesButton" style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; margin: 5px; border-radius: 5px; cursor: pointer;">Yes</button>
                        <button id="noButton" style="background-color: #f44336; color: white; border: none; padding: 10px 20px; margin: 5px; border-radius: 5px; cursor: pointer;">No</button>
                    </div>
                `;
                $("#" + appointmentPromptId + " .message-content").append(buttonsHtml);

                $("#yesButton").click(function () {
                    $("#appointment-buttons").remove();
                    addMessage('Yes, I would like to book an online appointment.', "user-message", "You", "https://ui-avatars.com/api/?name=User&background=random&color=ffffff");

                    // Fetch and display the doctors
                    $.post('/chat/summarize', function(response) {
                        displayDoctors(response.doctors);
                    });
                });

                $("#noButton").click(function () {
                    // Handle No button click
                    $("#appointment-buttons").remove();
                    addMessage('No thank you. I have no further questions.', "user-message", "You", "https://ui-avatars.com/api/?name=Nurul&background=random&color=ffffff");
                });

                // Add the text-to-speech button after the full response is received
                addTextToSpeechButton(responseBubbleId, fullResponse);

                return;
            }

            const data = JSON.parse(event.data);
            if (data.text) {
                if (isFirstUpdate) {
                    // Remove loading state on first update
                    clearMessageContent(responseBubbleId);
                    isFirstUpdate = false;
                }
                fullResponse += data.text;
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

        if (message.toLowerCase().includes('book appointment') || message.toLowerCase().includes('schedule appointment')) {
            $.post('/chat/summarize', function(response) {
                displayDoctors(response.doctors);
            });
        }
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

    function displayDoctors(doctors) {
        let doctorListHtml = '<div class="doctor-list">';
        doctors.forEach(doctor => {
            doctorListHtml += `
                <div class="doctor-card">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(doctor.name)}&background=random&color=ffffff" alt="${doctor.name}" class="doctor-avatar">
                    <div class="doctor-info">
                        <h3>${doctor.name}</h3>
                        <p>${doctor.expertise}</p>
                        <button onclick="openBookingModal(${doctor.id})">Book Appointment</button>
                    </div>
                </div>
            `;
        });
        doctorListHtml += '</div>';

        addMessage(doctorListHtml, "claude-message", "MySihat Bot", "/build/assets/mysihatbot.png");
    }

    function openBookingModal(doctorId) {
        $('#bookingModal').modal('show');
        $('#selectedDoctorId').val(doctorId);
    }

    function submitAppointment() {
        const form = document.getElementById('appointmentForm');
        const formData = new FormData(form);

        $.ajax({
            url: '/appointments',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#bookingModal').modal('hide');
                displayMessage("Appointment booked successfully!", "claude-message");
            },
            error: function(xhr, status, error) {
                alert("Error booking appointment: " + error);
            }
        });
    }

    function displayMessage(message, className) {
        addMessage(message, className, "MySihat Bot", "/build/assets/mysihatbot.png");
    }

    function addTextToSpeechButton(id, message) {
        const buttonHtml = `
            <div class="text-to-speech-wrapper">
                <button class="btn text-to-speech" data-message="${stripTags(message)}">🔊</button>
                <button class="btn stop-tts" style="display: none;">⏹️</button>
            </div>
        `;
        $("#" + id + " .message-content").append(buttonHtml);
    }

    function stripTags(input) {
        const div = document.createElement('div');
        div.innerHTML = input;
        return div.textContent || div.innerText || '';
    }

    function addMessage(message, className, sender, avatarUrl, id = null) {
        const chatMessages = document.getElementById('chatMessages');
        const messageHtml = `
            <div class="message ${className}" ${id ? `id="${id}"` : ''}>
                <div class="message-avatar">
                    <img src="${avatarUrl}" alt="${sender} avatar" class="rounded-full w-10 h-10">
                </div>
                <div class="message-content-wrapper">
                    <div class="message-sender">${sender}</div>
                    <div class="message-content">
                        ${message}
                        ${className === 'claude-message' ? `
                        <div class="text-to-speech-wrapper">
                            <button class="btn text-to-speech" data-message="${stripTags(message)}">🔊</button>
                            <button class="btn stop-tts" style="display: none;">⏹️</button>
                        </div>` : ''}
                    </div>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
    }
});