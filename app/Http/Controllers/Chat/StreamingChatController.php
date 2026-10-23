<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class StreamingChatController extends Controller
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
You are MySihat Bot, a friendly and knowledgeable virtual care assistant for MySihat, a medical app promoting healthcare inclusivity, especially in rural areas. Automatically detect the dominant language of the user's latest message and reply in that same language. Support English, Bahasa Melayu, Tamil, and Simplified Chinese. Never ask the user to choose a language and do not mix languages unless the user does. Sustain a natural, compassionate multi-turn conversation: ask one useful follow-up question at a time when details are missing, acknowledge answers, and help the user decide whether a clinician is appropriate. Do not diagnose with certainty and do not replace a medical professional. Mention that they can choose a doctor whenever they are ready, but never pressure them or end the conversation just because booking is available. If symptoms could indicate an emergency, tell the user to seek urgent local medical care immediately. Format responses with simple Markdown and keep medical terms easy to understand.
PROMPT;

    public function index(Request $request)
    {
        $question = trim((string) $request->query('question', ''));

        if ($question === '') {
            return response()->json(['message' => 'A question is required.'], 422);
        }

        $conversation = Session::get('conversation', []);
        $conversation[] = ['role' => 'user', 'content' => $question];

        return response()->stream(function () use (&$conversation) {
            try {
                $answer = $this->complete(self::SYSTEM_PROMPT, $conversation, 1024);
                $conversation[] = ['role' => 'assistant', 'content' => $answer];
                Session::put('conversation', $conversation);
                Session::save();

                $this->send('update', ['text' => $answer]);
                $this->send('done', ['complete' => true]);
            } catch (\Throwable $exception) {
                Log::error('OpenAI chat error', ['message' => $exception->getMessage()]);
                $this->send('error', ['error' => 'The chat service is temporarily unavailable. Please try again shortly.']);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function summarizeAndStore(Request $request)
    {
        $conversation = Session::get('conversation', []);

        if ($conversation === []) {
            return response()->json(['message' => 'Start a conversation before requesting a summary.'], 422);
        }

        try {
            $summary = $this->complete(
                'Summarize this medical conversation in concise Markdown. Include symptoms, relevant history, medications, advice, and next steps. Use only information in the conversation.',
                $conversation,
                500,
            );
        } catch (\Throwable $exception) {
            Log::error('OpenAI summary error', ['message' => $exception->getMessage()]);

            return response()->json(['message' => 'The chat service is temporarily unavailable.'], 503);
        }

        $expertise = $this->extractExpertiseFromSummary($summary);
        $doctors = $this->getDoctorsByExpertise($expertise);
        $this->storeSummary($request->user()->id, $summary);

        return response()->json(compact('summary', 'doctors'));
    }

    private function complete(string $instructions, array $conversation, int $maxOutputTokens): string
    {
        $apiKey = config('services.openai.key');

        if (! is_string($apiKey) || $apiKey === '') {
            throw new \RuntimeException('OPENAI_KEY is not configured.');
        }

        $response = Http::acceptJson()
            ->withToken($apiKey)
            ->timeout(60)
            ->post('https://api.openai.com/v1/responses', [
                'model' => config('services.openai.model'),
                'instructions' => $instructions,
                'input' => array_map(fn (array $message) => [
                    'role' => $message['role'],
                    'content' => $message['content'],
                ], $conversation),
                'max_output_tokens' => $maxOutputTokens,
            ]);

        if ($response->failed()) {
            Log::warning('OpenAI request failed', ['status' => $response->status()]);
            throw new \RuntimeException('OpenAI request failed.');
        }

        $text = trim((string) $response->json('output_text', ''));

        if ($text === '') {
            foreach ($response->json('output', []) as $item) {
                foreach ($item['content'] ?? [] as $content) {
                    if (($content['type'] ?? null) === 'output_text' && isset($content['text'])) {
                        $text .= $content['text'];
                    }
                }
            }
            $text = trim($text);
        }

        if ($text === '') {
            throw new \RuntimeException('OpenAI returned an empty response.');
        }

        return $text;
    }

    private function send(string $event, array $data): void
    {
        echo "event: {$event}\n";
        echo 'data: '.json_encode($data, JSON_UNESCAPED_UNICODE)."\n\n";

        if (ob_get_level() > 0) {
            ob_flush();
        }

        flush();
    }

    private function storeSummary(int $userId, string $summary): void
    {
        try {
            DB::table('medical_records')->insert([
                'user_id' => $userId,
                'summary' => $summary,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Medical-record storage error', ['message' => $exception->getMessage()]);
        }
    }

    private function extractExpertiseFromSummary(string $summary): string
    {
        $keywords = [
            'Cardiologist' => ['heart', 'chest pain', 'palpitations', 'hypertension'],
            'Dermatologist' => ['skin', 'rash', 'acne', 'eczema'],
            'Endocrinologist' => ['diabetes', 'thyroid', 'hormone'],
            'Gastroenterologist' => ['stomach', 'digestion', 'nausea', 'diarrhea'],
            'Neurologist' => ['headache', 'migraine', 'seizure', 'stroke'],
            'Orthopedic Surgeon' => ['joint', 'fracture', 'sprain', 'bone'],
            'Pediatrician' => ['child', 'infant', 'newborn'],
            'Psychiatrist' => ['anxiety', 'depression', 'mental health', 'stress'],
            'Pulmonologist' => ['lung', 'breathing', 'asthma', 'cough'],
        ];

        foreach ($keywords as $expertise => $terms) {
            foreach ($terms as $term) {
                if (str_contains(mb_strtolower($summary), $term)) {
                    return $expertise;
                }
            }
        }

        return 'General Practitioner';
    }

    private function getDoctorsByExpertise(string $expertise)
    {
        return User::query()
            ->where('type', 2)
            ->when($expertise !== 'General Practitioner', fn ($query) => $query->where('expertise', $expertise))
            ->limit(6)
            ->get(['id', 'name', 'expertise']);
    }
}
