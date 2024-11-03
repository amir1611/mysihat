<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZoomController extends Controller
{
    public function createMeeting(Request $request)
    {
        // Validate input
        $validated = $this->validate($request, [
            'title' => 'required',
            'start_date_time' => 'required|date',
            'duration_in_minute' => 'required|numeric',
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.self::generateToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.zoom.us/v2/users/me/meetings', [
                'topic' => $validated['title'],
                'type' => 2, // 2 for scheduled meeting
                'start_time' => Carbon::parse($validated['start_date_time'])->toIso8601String(),
                'duration' => $validated['duration_in_minute'],
            ]);

            return $response->json();

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }

    protected function generateToken(): string
    {
        try {
            $base64String = base64_encode(env('ZOOM_CLIENT_ID').':'.env('ZOOM_CLIENT_SECRET'));
            $accountId = env('ZOOM_ACCOUNT_ID');

            $responseToken = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Basic {$base64String}",
            ])->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id={$accountId}");

            return $responseToken->json()['access_token'];

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
