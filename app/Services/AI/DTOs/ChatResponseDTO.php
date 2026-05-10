<?php

namespace App\Services\AI\DTOs;

class ChatResponseDTO
{
    public function __construct(
        public readonly bool $success,
        public readonly string $reply,
        public readonly bool $isLeadGenerated = false,
        public readonly ?array $metadata = []
    ) {}

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'reply' => $this->reply,
            'is_lead' => $this->isLeadGenerated,
            'metadata' => $this->metadata,
        ];
    }
}
