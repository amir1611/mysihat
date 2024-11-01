<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class BmiCalculator extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static string $view = 'filament.pages.bmi-calculator';

    protected static ?string $title = 'BMI Calculator';

    protected static ?string $navigationGroup = 'Calculators';

    protected $user;

    protected int $age;

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->age = Carbon::parse($this->user->date_of_birth)->age;
    }
}
