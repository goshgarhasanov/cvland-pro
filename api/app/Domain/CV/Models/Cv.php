<?php

declare(strict_types=1);

namespace App\Domain\CV\Models;

use App\Domain\Catalog\Models\Template;
use App\Domain\CV\Enums\CvStatus;
use App\Domain\Identity\Models\User;
use Database\Factories\CvFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'template_id', 'title', 'slug', 'status', 'data', 'is_public', 'published_at'])]
#[UseFactory(CvFactory::class)]
class Cv extends Model
{
    /** @use HasFactory<CvFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => CvStatus::class,
            'data' => 'array',
            'is_public' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CvSection::class)->orderBy('position');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', CvStatus::Published)->whereNotNull('published_at');
    }
}
