document.addEventListener('DOMContentLoaded', function() {
    const micButton = document.getElementById('micButton');
    const messageInput = document.getElementById('messageInput');
    
    // Check if browser supports speech recognition
    if ('webkitSpeechRecognition' in window) {
        const recognition = new webkitSpeechRecognition();
        
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        let isListening = false;

        micButton.addEventListener('click', function() {
            if (!isListening) {
                // Start listening
                recognition.start();
                micButton.innerHTML = '<i class="fas fa-stop"></i>';
                micButton.classList.add('listening');
                isListening = true;
            } else {
                // Stop listening
                recognition.stop();
                micButton.innerHTML = '<i class="fas fa-microphone"></i>';
                micButton.classList.remove('listening');
                isListening = false;
            }
        });

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            messageInput.value = transcript;
            
            // Automatically send message after speech recognition
            document.getElementById('sendButton').click();
            
            // Reset mic button
            micButton.innerHTML = '<i class="fas fa-microphone"></i>';
            micButton.classList.remove('listening');
            isListening = false;
        };

        recognition.onerror = function(event) {
            console.error('Speech recognition error:', event.error);
            micButton.innerHTML = '<i class="fas fa-microphone"></i>';
            micButton.classList.remove('listening');
            isListening = false;
        };

    } else {
        micButton.style.display = 'none';
        console.log('Speech recognition not supported');
    }
});

