<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Models;

use App\Domain\CV\Models\Cv;
use Database\Factories\TemplateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'description',
    'preview_path',
    'category',
    'price_minor',
    'currency',
    'is_active',
    'metadata',
])]
#[UseFactory(TemplateFactory::class)]
class Template extends Model
{
    /** @use HasFactory<TemplateFactory> */
    use HasFactory;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price_minor' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function cvs(): HasMany
    {
        return $this->hasMany(Cv::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Price in major units (e.g. 4.99 from 499). */
    public function priceMajor(): float
    {
        return $this->price_minor / 100;
    }
}
