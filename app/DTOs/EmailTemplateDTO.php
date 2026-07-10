<?php

namespace App\DTOs;

class EmailTemplateDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $subject,
        public readonly string $bodyHtml,
        public readonly ?string $bodyText = null,
        public readonly ?array $variables = [],
        public readonly ?array $builderJson = null,
        public readonly string $type = 'download',
        public readonly bool $isActive = true,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            subject: $data['subject'],
            bodyHtml: $data['body_html'],
            bodyText: $data['body_text'] ?? null,
            variables: $data['variables'] ?? [],
            builderJson: isset($data['builder_json']) ? (is_array($data['builder_json']) ? $data['builder_json'] : json_decode($data['builder_json'], true)) : null,
            type: $data['type'] ?? 'download',
            isActive: (bool) ($data['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'subject' => $this->subject,
            'body_html' => $this->bodyHtml,
            'body_text' => $this->bodyText,
            'variables' => $this->variables,
            'builder_json' => $this->builderJson,
            'type' => $this->type,
            'is_active' => $this->isActive,
        ];
    }
}
