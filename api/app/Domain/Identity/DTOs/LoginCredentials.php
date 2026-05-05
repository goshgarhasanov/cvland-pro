<?php

declare(strict_types=1);

namespace App\Domain\Identity\DTOs;

final readonly class LoginCredentials
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}

    /** @param  array<string, mixed>  $data */
    public static function fromArray(array $data): self
    {
        return new self(
            email: mb_strtolower(trim((string) ($data['email'] ?? ''))),
            password: (string) ($data['password'] ?? ''),
            remember: (bool) ($data['remember'] ?? false),
        );
    }

    /** @return array<string, string> */
    public function toCredentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
