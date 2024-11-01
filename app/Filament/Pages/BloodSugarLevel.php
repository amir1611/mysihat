<?php

namespace App\Filament\Pages;

use App\Models\BloodSugarLevel as ModelsBloodSugarLevel;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class BloodSugarLevel extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string $view = 'filament.pages.blood-sugar-level';

    protected static ?string $title = 'Blood Sugar Level Calculator';

    protected static ?string $navigationGroup = 'Calculators';

    public User $user;

    public ?int $age = 0;

    public ?array $levels = [];

    public ?float $averageLevel = 0;

    public ?string $averageStatus = '';

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->age = Carbon::parse($this->user->date_of_birth)->age;
        $levels = ModelsBloodSugarLevel::where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc') // Order by most recent
            ->get();
        // Analyze each level and add status
        foreach ($levels as $level) {
            $level->status = $this->analyzeBloodSugar($level->level);
        }
        // Calculate the average blood sugar level
        $this->averageLevel = $levels->avg('level');
        $this->averageStatus = $this->analyzeBloodSugar($this->averageLevel); // Get status based on average

    }

    private function analyzeBloodSugar($level)
    {
        // Adjust thresholds for mmol/L
        if ($level < 4.0) {
            return 'low';
        } // Below 4.0 mmol/L is low
        if ($level >= 4.0 && $level <= 7.8) {
            return 'normal';
        } // 4.0 to 7.8 mmol/L is normal

        return 'high'; // Above 7.8 mmol/L is high
    }

    private function getHealthRisk($status)
    {
        switch ($status) {
            case 'low':
                return 'Consider consuming fast-acting carbohydrates.';
            case 'normal':
                return 'Keep up the good work!';
            case 'high':
                return 'Consult a healthcare provider.';
            default:
                return 'Unknown status.';
        }
    }
}
