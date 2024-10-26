document.addEventListener('DOMContentLoaded', function() {
    let voices = [];
    let selectedVoice = null;
    let currentUtterance = null;
    let currentStopButton = null;

    // Load available voices
    function loadVoices() {
        voices = window.speechSynthesis.getVoices();
        // Select a different voice (e.g., the second voice in the list)
        selectedVoice = voices.find(voice => voice.name.includes('Google UK English Male')) || voices[1];
    }

    // Load voices when they are available
    if (window.speechSynthesis.onvoiceschanged !== undefined) {
        window.speechSynthesis.onvoiceschanged = loadVoices;
    }

    // Event delegation to handle dynamically added buttons
    document.body.addEventListener('click', function(event) {
        if (event.target.classList.contains('text-to-speech')) {
            const button = event.target;
            let message = button.getAttribute('data-message');

            // Sanitize the message to remove special characters like asterisks
            message = message.replace(/[*]/g, '');

            const utterance = new SpeechSynthesisUtterance(message);
            currentUtterance = utterance;
            if (selectedVoice) {
                utterance.voice = selectedVoice;
            }

            // Disable all TTS buttons while speaking
            document.querySelectorAll('.text-to-speech').forEach(btn => btn.disabled = true);
            const wrapper = button.closest('.text-to-speech-wrapper');
            const stopButton = wrapper.querySelector('.stop-tts');
            stopButton.style.display = 'inline';
            currentStopButton = stopButton;

            utterance.onend = function() {
                document.querySelectorAll('.text-to-speech').forEach(btn => btn.disabled = false);
                stopButton.style.display = 'none';
                currentStopButton = null;
            };

            window.speechSynthesis.speak(utterance);
        }

        if (event.target.classList.contains('stop-tts')) {
            window.speechSynthesis.cancel();
            const wrapper = event.target.closest('.text-to-speech-wrapper');
            const ttsButton = wrapper.querySelector('.text-to-speech');
            event.target.style.display = 'none';
            if (currentUtterance) {
                currentUtterance.onend();
            }
            ttsButton.disabled = false;
            currentStopButton = null;
        }
    });
});