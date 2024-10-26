<div class="message {{ $className }}" id="{{ $id ?? '' }}">
    <div class="message-avatar">
        <img src="{{ $avatarUrl }}" alt="{{ $sender }} avatar" class="rounded-full w-10 h-10">
    </div>
    <div class="message-content-wrapper">
        <div class="message-sender">{{ $sender }}</div>
        <div class="message-content">
            {!! $message !!}
            @if ($className === 'claude-message')
                <div class="text-to-speech-wrapper">
                    <button class="btn text-to-speech" data-message="{{ strip_tags($message) }}">🔊</button>
                    <button class="btn stop-tts" style="display: none;">⏹️</button>
                </div>
            @endif
        </div>
    </div>
</div>