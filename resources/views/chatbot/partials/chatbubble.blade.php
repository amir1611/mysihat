<div class="message {{ $className }}" id="{{ $id ?? '' }}">
    <div class="message-avatar">
        <img src="{{ $avatarUrl }}" alt="{{ $sender }} avatar" class="rounded-full w-10 h-10">
    </div>
    <div class="message-content-wrapper">
        <div class="message-sender">{{ $sender }}</div>
        <div class="message-content">{!! $message !!}</div>
    </div>
</div>
