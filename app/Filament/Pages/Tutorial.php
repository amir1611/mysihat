<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class Tutorial extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static string $view = 'filament.pages.tutorial';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = 'Learning';
}
