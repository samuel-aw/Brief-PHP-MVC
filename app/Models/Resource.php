<?php

namespace App\Models;

class Resource {

    public const STATUSES = [
        'disponible' => 'Disponible',
        'emprunte' => 'Emprunté',
        'maintenance' => 'Maintenance',
    ];

    public function __construct(
        public string $title,
        public string $type,
        public string $status = 'disponible',
        public ?string $borrower = null,
        public ?int $id = null,
        public ?string $createdAt = null,
    ){}

    public static function fromArray(array $data): self {
        $borrower = trim((string) ($data['borrower'] ?? ''));

        return new self(
            title: trim((string) ($data['title'] ?? '')),
            type: trim((string) ($data['type'] ?? '')),
            status: $data['status'] ?? 'disponible',
            borrower: $borrower === '' ? null : $borrower,
            id: isset($data['id']) ? (int) $data['id'] : null,
            createdAt: $data['created_at'] ?? null,
        );
    }
}