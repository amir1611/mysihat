<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class HealthArticle extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static string $view = 'filament.pages.health-article';

    protected static ?int $navigationSort = 4;

    public function getTitle(): string
    {
        return 'Health News Articles';
    }
}
