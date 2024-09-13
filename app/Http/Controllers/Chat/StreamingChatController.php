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
    private function send($event, $data)
    {
        echo "event: {$event}\n";
        echo 'data: ' . $data;
        echo "\n\n";
        ob_flush();
        flush();
    }

    public function index(Request $request)
    {
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
                $system_prompt = "You are Dr. AI, a friendly and knowledgeable virtual doctor for MySihat, a medical app promoting healthcare inclusivity, especially in rural areas.
                                  Your role is to provide initial advice and potential diagnoses based on symptoms described by users.
                                  Be empathetic, clear, and concise in your responses, using language that is easily understood by a diverse audience.
                                  While offering helpful information, always remind users that for a definitive diagnosis and treatment plan, they should book an appointment with a healthcare professional through the MySihat app.
                                  At the end of each conversation, encourage users to utilize the app's booking feature for in-person or telemedicine consultations.
                                  Act as a caring human doctor speaking directly to a patient, keeping in mind the goal of improving healthcare access for underserved communities.
                                  You are also able to speak Bahasa Malaysia and English fluently, catering to a diverse user base.

                                When formatting your responses:
                                1. Use markdown syntax for emphasis and structure.
                                2. For lists, use a dash (-) followed by a space for each item.
                                3. For code blocks, use triple backticks (```) to open and close the block.
                                4. Use single backticks (`) for inline code or technical terms.
                                6. Avoid unnecessary line breaks within paragraphs.
                                7. Use double newlines to separate distinct sections or ideas.

                                Adhere to these formatting guidelines to ensure your responses are well-structured and easy to parse.";

                // This is a test system prompt for the chatbot to reduce Claude's usage;
                // $system_prompt = "You only reply with 5 words or less.";

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

                    $buffer = '';
                    $timeout = microtime(true);
                    $delayLimit = 5; // More responsive streaming
                    $last_stream_response = null;
                    $markdown_block = false;
                    $code_block = false;
                    $list_item = false;

                    foreach ($stream as $response) {
                        $text = $response->choices[0]->delta->content;
                        $buffer .= $text;

                        if (connection_aborted()) {
                            break;
                        }

                        // Check for markdown-specific patterns
                        if (preg_match('/```/', $text)) {
                            $code_block = !$code_block;
                        }
                        if (preg_match('/^\s*[-*+]\s/', $text)) {
                            $list_item = true;
                        }
                        if (preg_match('/^\s*#/', $text)) {
                            $markdown_block = true;
                        }

                        $should_send = false;

                        // Determine if we should send the buffer
                        if ($code_block && preg_match('/\n$/', $buffer)) {
                            $should_send = true; // Send at the end of each line in a code block
                        } elseif ($list_item && preg_match('/\n$/', $buffer)) {
                            $should_send = true; // Send at the end of each list item
                        } elseif ($markdown_block && preg_match('/\n\s*$/', $buffer)) {
                            $should_send = true; // Send after a newline following a markdown block
                        } elseif (preg_match('/[.!?]\s*$/', $buffer)) {
                            $should_send = true; // Send at the end of a sentence
                        } elseif ((microtime(true) - $timeout) > $delayLimit) {
                            $should_send = true; // Send after delay limit
                        }

                        if ($should_send) {
                            $this->send("update", json_encode(['text' => $buffer]));
                            $buffer = '';
                            $timeout = microtime(true);
                            $markdown_block = false;
                            $list_item = false;
                        }

                        $last_stream_response = $response;
                    }

                    // Send any remaining buffer
                    if (!empty($buffer)) {
                        $this->send("update", json_encode(['text' => $buffer]));
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
