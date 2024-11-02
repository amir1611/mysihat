<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class HealthTool extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal';

    protected static string $view = 'filament.pages.health-tool';

    protected static ?string $title = 'Health Tools';

    protected static ?int $navigationSort = 5;

    protected function toBmiCalculator(): string
    {
        return BmiCalculator::getUrl();
    }

    protected function toBloodSugarCalculator(): string
    {
        return BloodSugarLevel::getUrl();
    }
}
