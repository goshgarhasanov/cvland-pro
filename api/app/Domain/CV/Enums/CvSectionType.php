<?php

declare(strict_types=1);

namespace App\Domain\CV\Enums;

enum CvSectionType: string
{
    case Summary = 'summary';
    case Experience = 'experience';
    case Education = 'education';
    case Skills = 'skills';
    case Projects = 'projects';
    case Certifications = 'certifications';
    case Languages = 'languages';
    case References = 'references';
    case Custom = 'custom';

    /** @return array<int, string> */
    public static function values(): array
    {
        return array_map(fn (self $case): string => $case->value, self::cases());
    }
}
