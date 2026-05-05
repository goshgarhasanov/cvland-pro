<?php

declare(strict_types=1);

namespace App\Domain\Identity\DTOs;

final readonly class RegisterUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $locale = 'en',
    ) {}

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            name: trim((string) ($data['name'] ?? '')),
            email: mb_strtolower(trim((string) ($data['email'] ?? ''))),
            password: (string) ($data['password'] ?? ''),
            locale: (string) ($data['locale'] ?? 'en'),
        );
    }
}
