<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Tutorial extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static string $view = 'filament.pages.tutorial';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = 'Learning';
}
