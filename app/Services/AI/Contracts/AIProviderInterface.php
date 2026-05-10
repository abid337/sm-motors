<?php

namespace App\Services\AI\Contracts;

interface AIProviderInterface
{
    /**
     * Send a prompt to the AI provider and return the response.
     *
     * @param array $messages
     * @param array $options
     * @return string
     */
    public function generateResponse(array $messages, array $options = []): string;
}
