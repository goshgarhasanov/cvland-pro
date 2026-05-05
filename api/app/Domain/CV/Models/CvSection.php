<?php

declare(strict_types=1);

namespace App\Domain\CV\Models;

use App\Domain\CV\Enums\CvSectionType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['cv_id', 'type', 'title', 'content', 'position'])]
class CvSection extends Model
{
    use HasUlids;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'type' => CvSectionType::class,
            'content' => 'array',
            'position' => 'integer',
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
