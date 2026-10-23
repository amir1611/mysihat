$(function () {
    const $messages = $('#chatMessages');
    const botAvatar = '/build/assets/mysihatbot.png';
    let streaming = false;

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $('#sendButton').on('click', sendMessage);
    $('#messageInput').on('keydown', function (event) { if (event.key === 'Enter') { event.preventDefault(); sendMessage(); } });
    $('#findDoctorButton, #findDoctorHelper').on('click', findDoctors);
    $('#appointmentForm').on('submit', submitAppointment);
    $('#appointmentDate').attr('min', new Date().toISOString().slice(0, 10));

    function sendMessage() {
        const message = $('#messageInput').val().trim();
        if (!message || streaming) return;
        addMessage(message, 'user-message', 'You');
        $('#messageInput').val('');
        getAIResponse(message);
    }

    function getAIResponse(message) {
        streaming = true; $('#sendButton').prop('disabled', true);
        const id = `ai-response-${Date.now()}`;
        addMessage('Thinking…', 'bot-message', 'MySihat Care Assistant', id);
        const source = new EventSource(`/chat/streaming?question=${encodeURIComponent(message)}`);
        let answer = '';
        source.addEventListener('update', (event) => {
            try { const data = JSON.parse(event.data); answer += data.text || ''; setMessageText(id, answer || 'Thinking…'); } catch (_) { setMessageText(id, 'Sorry, I could not read that response.'); }
        });
        source.addEventListener('done', () => { source.close(); finishResponse(id, answer); });
        source.addEventListener('error', () => { source.close(); if (!answer) setMessageText(id, 'Sorry, the care assistant is temporarily unavailable. Please try again.'); finishResponse(id, answer); });
    }

    function finishResponse(id, answer) {
        streaming = false; $('#sendButton').prop('disabled', false); $('#messageInput').trigger('focus');
        if (answer) addTtsButton(id, answer);
        scrollToBottom();
    }

    function findDoctors() {
        if (streaming) { addMessage('I will suggest doctors as soon as I finish my current reply.', 'bot-message', 'MySihat Care Assistant'); return; }
        addMessage('I’m ready to choose a doctor.', 'user-message', 'You');
        const loadingId = `doctor-loading-${Date.now()}`;
        addMessage('I’m matching you with the right care team…', 'bot-message', 'MySihat Care Assistant', loadingId);
        $.post('/chat/summarize').done((response) => {
            $(`#${loadingId}`).remove();
            displayDoctors(response.doctors || []);
        }).fail((xhr) => {
            $(`#${loadingId}`).remove();
            addMessage(xhr.responseJSON?.message || 'Please share a little about your concern first, then I can match you with a doctor.', 'bot-message', 'MySihat Care Assistant');
        });
    }

    function displayDoctors(doctors) {
        if (!doctors.length) { addMessage('I could not find a matching doctor right now. Please use the appointment page to view available clinicians.', 'bot-message', 'MySihat Care Assistant'); return; }
        const id = `doctor-results-${Date.now()}`;
        addMessage('Here are clinicians who can help. Choose the one you prefer:', 'bot-message', 'MySihat Care Assistant', id);
        const $list = $('<div>', { class: 'doctor-list' });
        doctors.forEach((doctor) => {
            const $card = $('<div>', { class: 'doctor-card' });
            $('<img>', { class: 'doctor-avatar', alt: '', src: `https://ui-avatars.com/api/?name=${encodeURIComponent(doctor.name)}&background=ecf3ff&color=254ea4` }).appendTo($card);
            const $info = $('<div>', { class: 'doctor-info' }).appendTo($card);
            $('<h3>').text(doctor.name).appendTo($info); $('<p>').text(doctor.expertise || 'General Practitioner').appendTo($info);
            $('<button>', { type: 'button', text: 'Book' }).on('click', () => openBookingModal(doctor)).appendTo($info);
            $list.append($card);
        });
        $(`#${id} .message-content`).append($list); scrollToBottom();
    }

    function openBookingModal(doctor) {
        $('#selectedDoctorId').val(doctor.id); $('#selectedDoctorName').text(`Booking with ${doctor.name}${doctor.expertise ? ` · ${doctor.expertise}` : ''}`);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).show();
    }

    function submitAppointment(event) {
        event.preventDefault(); const $button = $('#bookAppointmentButton'); $button.prop('disabled', true).text('Booking…');
        $.ajax({ url: '/appointments', type: 'POST', data: new FormData(event.currentTarget), processData: false, contentType: false })
            .done(() => { bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).hide(); addMessage('Your appointment request is booked. You can see it any time in Appointments.', 'bot-message', 'MySihat Care Assistant'); event.currentTarget.reset(); })
            .fail((xhr) => { const errors = xhr.responseJSON?.errors; const message = errors ? Object.values(errors).flat().join(' ') : (xhr.responseJSON?.message || 'Unable to book the appointment. Please check the details and try again.'); alert(message); })
            .always(() => $button.prop('disabled', false).text('Confirm booking'));
    }

    function addMessage(message, className, sender, id = null) {
        const $message = $('<div>', { class: `message ${className}`, id: id || undefined });
        if (className !== 'user-message') $('<div>', { class: 'message-avatar' }).append($('<img>', { src: botAvatar, alt: '' })).appendTo($message);
        const $wrap = $('<div>', { class: 'message-content-wrapper' }).appendTo($message);
        $('<div>', { class: 'message-sender', text: sender }).appendTo($wrap); $('<div>', { class: 'message-content', text: message }).appendTo($wrap);
        $messages.append($message); scrollToBottom(); return $message;
    }
    function setMessageText(id, text) { $(`#${id} .message-content`).text(text); scrollToBottom(); }
    function addTtsButton(id, text) { const $content = $(`#${id} .message-content`); if (!$content.find('.text-to-speech').length) $content.append($('<div>', { class: 'text-to-speech-wrapper' }).append($('<button>', { class: 'btn text-to-speech', type: 'button', text: '🔊 Listen' }).attr('data-message', text))); }
    function scrollToBottom() { $messages.scrollTop($messages.prop('scrollHeight')); }
});
