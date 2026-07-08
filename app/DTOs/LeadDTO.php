<?php

namespace App\DTOs;

class LeadDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $phone = null,
        public readonly ?string $company = null,
        public readonly ?string $country = null,
        public readonly ?int $ebookId = null,
        public readonly bool $consent = false,
        public readonly ?array $utmParams = [],
        public readonly ?string $sourcePage = null,
        public readonly ?string $ipAddress = null,
        public readonly ?string $userAgent = null,
    ) {}

    public static function fromRequest(array $data, ?array $utmParams = []): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
            company: $data['company'] ?? null,
            country: $data['country'] ?? null,
            ebookId: $data['ebook_id'] ?? null,
            consent: (bool) ($data['consent'] ?? false),
            utmParams: $utmParams,
            sourcePage: $data['source_page'] ?? url()->previous(),
            ipAddress: request()->ip(),
            userAgent: request()->userAgent(),
        );
    }

    public function toArray(): array
    {
        return [
            'full_name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'country' => $this->country,
            'ebook_id' => $this->ebookId,
            'consent' => $this->consent,
            'utm_source' => $this->utmParams['utm_source'] ?? null,
            'utm_medium' => $this->utmParams['utm_medium'] ?? null,
            'utm_campaign' => $this->utmParams['utm_campaign'] ?? null,
            'utm_term' => $this->utmParams['utm_term'] ?? null,
            'utm_content' => $this->utmParams['utm_content'] ?? null,
            'source_page' => $this->sourcePage,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
        ];
    }
}
