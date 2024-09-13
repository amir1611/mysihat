<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Anthropic\Anthropic;

class StreamingChatController extends Controller
{
    protected $anthropic;

    public function __construct()
    {
        $headers = [
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
            'x-api-key' => env('ANTHROPIC_API_KEY')
        ];

        $this->anthropic = Anthropic::factory()
            ->withHeaders($headers)
            ->make();
    }

    /**
     * Sending server-events
     */
    private function send($event, $data) {
        echo "event: {$event}\n";
        echo 'data: ' . $data;
        echo "\n\n";
        ob_flush();
        flush();
    }

    public function index(Request $request) {
        $question = $request->query('question');
        return response()->stream(
            function () use (
                $question
            ) {
                set_time_limit(0); // To prevent script timeout
                ignore_user_abort(true); // To keep the script running even if the user closes the browser

                $result_text = "";
                $last_stream_response = null;

                /**
                 * Sets the model and maximum tokens for the Claude chatbot.
                 *
                 * The model used is 'claude-3-sonnet-20240229' and the maximum number of tokens allowed is 1024.
                 * Available models: https://docs.anthropic.com/en/docs/about-claude/models
                 */
                $model = 'claude-3-sonnet-20240229';
                $max_tokens = 1024;

                /**
                 *
                 * This is a system prompt for the Dr AI.
                 */
                // $system_prompt = "You are Dr. AI, a friendly and knowledgeable medical doctor.
                //                   Your job is to provide advice and potential diagnoses based on the symptoms described by the user.
                //                   Always be empathetic, clear, and concise in your responses.
                //                   Remember to remind users that while you can provide helpful information, they should book and consult with a healthcare professional in the app itself for a definitive diagnosis and treatment plan.";

                // This is a test system prompt for the chatbot to reduce Claude's usage;
                $system_prompt = "You only reply with 5 words or less.";

                /**
                 * @var float $temperature The temperature setting for the chatbot's response generation.
                 *                         Higher values make the output more random, while lower values
                 *                         make it more focused and deterministic.
                 */
                $temperature = 0.5;
                $messages = [
                    [
                        'role' => 'user',
                        'content' => $question
                    ]
                ];

                try {
                    $stream = $this->anthropic->chat()->createStreamed([
                        'model' => $model,
                        'system' => $system_prompt,
                        'max_tokens' => $max_tokens,
                        'temperature' => $temperature,
                        'messages' => $messages,
                        'stream' => true
                    ]);

                    foreach ($stream as $response) {
                        $text = $response->choices[0]->delta->content;
                        if (connection_aborted()) {
                            break;
                        }
                        $data = [
                            'text' => $text
                        ];
                        $this->send("update", json_encode($data));
                        $result_text = $text;
                        $last_stream_response = $response;
                    }

                    $this->send("update", "<END_STREAMING_SSE>");
                    logger($last_stream_response->usage->toArray());
                } catch (\Exception $e) {
                    logger()->error('Anthropic API Error: ' . $e->getMessage());
                    $this->send("error", json_encode(['error' => $e->getMessage()]));
                }
            },
            200,
            [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'X-Accel-Buffering' => 'no'
            ]
            );
    }
}
