<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiController extends Controller
{
    private $apiKey;
    private $baseUrl = 'https://generativelanguage.googleapis.com';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /**
     * Generate text using Gemini AI
     */
    public function generateText(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
            'model' => 'sometimes|string|in:gemini-1.5-flash,gemini-1.5-pro'
        ]);

        $model = $request->get('model', 'gemini-1.5-flash');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/v1beta/models/{$model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $request->prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $generatedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated';
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'text' => $generatedText,
                        'model' => $model,
                        'prompt_length' => strlen($request->prompt)
                    ]
                ]);
            }

            Log::error('Gemini API Error: ' . $response->body());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate content',
                'error' => $response->json()['error']['message'] ?? 'Unknown error'
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate content with image analysis
     */
    // public function analyzeImage(Request $request)
    // {
    //     $request->validate([
    //         'prompt' => 'required|string|max:1000',
    //         'image' => 'required|image|max:10240', // 10MB max
    //         'model' => 'sometimes|string|in:gemini-1.5-flash,gemini-1.5-pro'
    //     ]);

    //     $model = $request->get('model', 'gemini-1.5-flash');

    //     try {
    //         $imageData = file_get_contents($request->file('image')->path());
    //         $mimeType = $request->file('image')->getMimeType();
            
    //         $response = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //         ])->post("{$this->baseUrl}/v1beta/models/{$model}:generateContent?key={$this->apiKey}", [
    //             'contents' => [
    //                 [
    //                     'parts' => [
    //                         ['text' => $request->prompt],
    //                         [
    //                             'inline_data' => [
    //                                 'mime_type' => $mimeType,
    //                                 'data' => base64_encode($imageData)
    //                             ]
    //                         ]
    //                     ]
    //                 ]
    //             ],
    //             'generationConfig' => [
    //                 'temperature' => 0.4,
    //                 'topK' => 32,
    //                 'topP' => 1,
    //                 'maxOutputTokens' => 4096,
    //             ]
    //         ]);

    //         if ($response->successful()) {
    //             $data = $response->json();
    //             $analysis = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No analysis generated';
                
    //             return response()->json([
    //                 'success' => true,
    //                 'data' => [
    //                     'analysis' => $analysis,
    //                     'model' => $model,
    //                     'image_size' => $request->file('image')->getSize(),
    //                     'image_type' => $mimeType
    //                 ]
    //             ]);
    //         }

    //         Log::error('Gemini Image API Error: ' . $response->body());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to analyze image',
    //             'error' => $response->json()['error']['message'] ?? 'Unknown error'
    //         ], $response->status());

    //     } catch (\Exception $e) {
    //         Log::error('Gemini Image API Exception: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while analyzing the image',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Chat conversation with Gemini
     */
    public function chat(Request $request)
    {
        $request->validate([
            'messages' => 'required|array|min:1',
            'messages.*.role' => 'required|string|in:user,model',
            'messages.*.content' => 'required|string',
            'model' => 'sometimes|string|in:gemini-1.5-flash,gemini-1.5-pro'
        ]);

        $model = $request->get('model', 'gemini-1.5-flash');

        try {
            // Convert messages to Gemini format
            $contents = [];
            foreach ($request->messages as $message) {
                $contents[] = [
                    'role' => $message['role'],
                    'parts' => [
                        ['text' => $message['content']]
                    ]
                ];
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/v1beta/models/{$model}:generateContent?key={$this->apiKey}", [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.8,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 2048,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated';
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'reply' => $reply,
                        'model' => $model,
                        'conversation_length' => count($request->messages)
                    ]
                ]);
            }

            Log::error('Gemini Chat API Error: ' . $response->body());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate chat response',
                'error' => $response->json()['error']['message'] ?? 'Unknown error'
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Gemini Chat API Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during chat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available models
     */
    public function models()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'models' => [
                    [
                        'name' => 'gemini-1.5-flash',
                        'description' => 'Fast and efficient model for most tasks',
                        'input_token_limit' => 1000000,
                        'output_token_limit' => 8192
                    ],
                    [
                        'name' => 'gemini-1.5-pro',
                        'description' => 'More capable model for complex tasks',
                        'input_token_limit' => 2000000,
                        'output_token_limit' => 8192
                    ]
                ]
            ]
        ]);
    }

    /**
     * Health check for Gemini API
     */
    public function health()
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/v1beta/models/gemini-1.5-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Hello']
                        ]
                    ]
                ]
            ]);

            return response()->json([
                'success' => $response->successful(),
                'status' => $response->status(),
                'api_available' => $response->successful()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'api_available' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}