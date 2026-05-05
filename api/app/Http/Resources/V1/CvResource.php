<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Domain\CV\Models\Cv;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Cv */
final class CvResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        /** @var Cv $cv */
        $cv = $this->resource;

        return [
            'id' => $cv->id,
            'title' => $cv->title,
            'slug' => $cv->slug,
            'status' => $cv->status->value,
            'is_public' => $cv->is_public,
            'content' => $cv->data ?? [],
            'template' => TemplateResource::make($this->whenLoaded('template')),
            'published_at' => $cv->published_at?->toIso8601String(),
            'created_at' => $cv->created_at?->toIso8601String(),
            'updated_at' => $cv->updated_at?->toIso8601String(),
        ];
    }
}
