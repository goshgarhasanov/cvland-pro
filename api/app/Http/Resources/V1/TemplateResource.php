<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Domain\Catalog\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Template */
final class TemplateResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'category' => $this->category,
            'preview_url' => $this->preview_path
                ? asset('storage/' . $this->preview_path)
                : null,
            'price' => [
                'minor' => $this->price_minor,
                'major' => $this->priceMajor(),
                'currency' => $this->currency,
                'formatted' => number_format($this->priceMajor(), 2) . ' ' . $this->currency,
            ],
        ];
    }
}
