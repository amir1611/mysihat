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
            ->orderBy('created_at', 'desc') 
            ->get();

        foreach ($levels as $level) {
            $level->status = $this->analyzeBloodSugar($level->level);
        }

  
        $averageLevel = $levels->avg('level');
        $averageStatus = $this->analyzeBloodSugar($averageLevel); 

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
        try {
            $request->validate([
                'blood_sugar' => 'required|numeric|min:0'
            ]);

            $bloodSugar = new BloodSugarLevel();
            $bloodSugar->user_id = Auth::id();
            $bloodSugar->level = $request->blood_sugar;
            $bloodSugar->save();

            $status = $this->analyzeBloodSugar($bloodSugar->level);
            $healthRisk = $this->getHealthRisk($status);

            return response()->json([
                'success' => true,
                'bloodSugarLevel' => $bloodSugar->level,
                'status' => $status,
                'healthRisk' => $healthRisk,
                'message' => 'Blood sugar level recorded successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error analyzing blood sugar level'
            ], 500);
        }
    }

    private function analyzeBloodSugar($level)
    {
       
        if ($level < 4.0) return 'low';
        if ($level >= 4.0 && $level <= 7.8) return 'normal'; 
        return 'high'; 
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
