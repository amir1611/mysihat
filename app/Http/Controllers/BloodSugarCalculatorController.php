<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\BloodSugarLevel;

class BloodSugarCalculatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $age = Carbon::parse($user->date_of_birth)->age;
        $levels = BloodSugarLevel::where('user_id', $user->id)
            ->orderBy('created_at', 'desc') // Order by most recent
            ->get();

        // Analyze each level and add status
        foreach ($levels as $level) {
            $level->status = $this->analyzeBloodSugar($level->level);
        }

        // Calculate the average blood sugar level
        $averageLevel = $levels->avg('level');
        $averageStatus = $this->analyzeBloodSugar($averageLevel); // Get status based on average

        return view('patient.blood-sugar-calculator', [
            'user' => $user,
            'age' => $age,
            'levels' => $levels,
            'averageLevel' => $averageLevel,
            'averageStatus' => $averageStatus,
        ]);
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'bloodSugarLevel' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $bloodSugarLevel = (float) $request->bloodSugarLevel; // Convert to float

        // Save the blood sugar level
        BloodSugarLevel::create([
            'user_id' => $user->id,
            'level' => $bloodSugarLevel,
        ]);

        // Analyze the blood sugar level
        $status = $this->analyzeBloodSugar($bloodSugarLevel);
        $healthRisk = $this->getHealthRisk($status);

        // Retrieve blood sugar levels for the user
        $levels = BloodSugarLevel::where('user_id', $user->id)
            ->orderBy('created_at', 'desc') // Order by most recent
            ->get();


        return response()->json([
            'bloodSugarLevel' => $bloodSugarLevel,
            'status' => $status,
            'healthRisk' => $healthRisk,
            'levels' => $levels, // Return all levels for the overview
        ]);
    }

    private function analyzeBloodSugar($level)
    {
        // Adjust thresholds for mmol/L
        if ($level < 4.0) return 'low'; // Below 4.0 mmol/L is low
        if ($level >= 4.0 && $level <= 7.8) return 'normal'; // 4.0 to 7.8 mmol/L is normal
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
