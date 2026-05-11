<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterProvider implements AIProviderInterface
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $model;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = config('ai.openrouter.api_key');
        $this->baseUrl = config('ai.openrouter.base_url');
        $this->model = config('ai.openrouter.default_model');
        $this->timeout = config('ai.openrouter.timeout', 45);
    }

    public function generateResponse(array $messages, array $options = []): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('ai.persona.name'),
            ])->timeout($this->timeout)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => $options['model'] ?? $this->model,
                    'messages' => $messages,
                    'temperature' => $options['temperature'] ?? config('ai.persona.temperature'),
                    'max_tokens' => $options['max_tokens'] ?? config('ai.persona.max_tokens'),
                ]);

            if (!$response->successful()) {
                Log::error('OpenRouter API Error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                
                $error = $response->json('error.message') ?? $response->body();
                return "AI Error: " . $error;
            }

            return $response->json('choices.0.message.content') ?? '';

        } catch (\Exception $e) {
            Log::error('OpenRouter Exception', ['message' => $e->getMessage()]);
            return "A technical error occurred. Please contact SM Autos support.";
        }
    }
}
