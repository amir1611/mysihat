<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BmiCalculatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $age = Carbon::parse($user->date_of_birth)->age;
        
        return view('patient.bmi-calculator', [
            'user' => $user,
            'age' => $age
        ]);
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $age = Carbon::parse($user->date_of_birth)->age;
        $weight = $request->weight;
        $height = $request->height / 100; 
        $bmi = $weight / ($height * $height);
        
        $category = $this->getBmiCategory($bmi);
        $healthRisk = $this->getHealthRisk($bmi);
        $idealWeightRange = $this->getIdealWeightRange($height);

        return response()->json([
            'bmi' => round($bmi, 1),
            'category' => $category,
            'healthRisk' => $healthRisk,
            'age' => $age,
            'gender' => $user->gender,
            'idealWeightRange' => $idealWeightRange
        ]);
    }

    private function getBmiCategory($bmi)
    {
        if ($bmi < 18.5) return 'Underweight';
        if ($bmi < 25) return 'Normal weight';
        if ($bmi < 30) return 'Overweight';
        if ($bmi < 35) return 'Moderately obese';
        if ($bmi < 40) return 'Severely obese';
        return 'Very severely obese';
    }

    private function getHealthRisk($bmi)
    {
        if ($bmi < 18.5) return 'Malnutrition risk';
        if ($bmi < 25) return 'Low risk';
        if ($bmi < 30) return 'Enhanced risk';
        if ($bmi < 35) return 'Medium risk';
        if ($bmi < 40) return 'High risk';
        return 'Very high risk';
    }

    private function getIdealWeightRange($height)
    {
        
        $minWeight = round(18.5 * ($height * $height), 1);
        $maxWeight = round(24.9 * ($height * $height), 1);
        return ['min' => $minWeight, 'max' => $maxWeight];
    }
}
