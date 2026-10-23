<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $this->ensureDoctor();

        return view('prescriptions.index', [
            'prescriptions' => Prescription::with('patient')->where('doctor_id', auth()->id())->latest()->get(),
            'patients' => User::where('type', 0)->orderBy('name')->get(['id', 'name', 'email']),
            'medicines' => collect(MarketplaceController::PRODUCTS)->where('requires_prescription', true),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureDoctor();
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'medicine_id' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10',
            'expires_at' => 'required|date|after:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $medicine = MarketplaceController::PRODUCTS[$request->medicine_id] ?? null;
        abort_unless($medicine && $medicine['requires_prescription'], 422, 'Select a prescription medicine.');
        abort_unless(User::whereKey($request->patient_id)->where('type', 0)->exists(), 422, 'Select a patient.');

        Prescription::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => auth()->id(),
            'medicine_id' => $request->medicine_id,
            'medicine_name' => $medicine['name'],
            'quantity' => $request->quantity,
            'expires_at' => $request->expires_at,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Prescription approved and available in the patient marketplace.');
    }

    private function ensureDoctor(): void { abort_unless(auth()->user()?->type === 2, 403); }
}
