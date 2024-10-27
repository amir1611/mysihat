<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ChatBot extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';

    protected static string $view = 'filament.pages.chat-bot';

    protected static ?string $navigationLabel = 'ChatBot';

    protected static ?string $title = '';

    protected static ?string $navigationBadgeTooltip = 'AI Medica Chatbot';


    public static function getNavigationBadge(): ?string
    {
        return 'AI';
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'edit';
    }

    public static function greeting(): string
    {
        $user = Auth::user();
        $greeting = "Hi {$user->name}. Welcome to MySihat, I am MySihat Bot. How can I assist you today?";
        return $greeting;
    }
}
