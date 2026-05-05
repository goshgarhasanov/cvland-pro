<?php

declare(strict_types=1);

namespace App\Domain\CV\DTOs;

use App\Domain\CV\Enums\CvStatus;

final readonly class CvData
{
    /** @param  array<string, mixed>  $data */
    public function __construct(
        public string $title,
        public ?int $templateId,
        public CvStatus $status,
        public array $data,
        public bool $isPublic,
    ) {}

    /** @param  array<string, mixed>  $input */
    public static function fromArray(array $input): self
    {
        return new self(
            title: trim((string) ($input['title'] ?? '')),
            templateId: isset($input['template_id']) ? (int) $input['template_id'] : null,
            status: CvStatus::from((string) ($input['status'] ?? CvStatus::Draft->value)),
            data: (array) ($input['data'] ?? []),
            isPublic: (bool) ($input['is_public'] ?? false),
        );
    }
}
