<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Catalog\Models\Template;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TemplateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class TemplateController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $templates = QueryBuilder::for(Template::class)
            ->active()
            ->allowedFilters(
                AllowedFilter::exact('category'),
                'name',
            )
            ->allowedSorts('name', 'price_minor', 'created_at')
            ->defaultSort('-created_at')
            ->paginate($request->integer('per_page', 24));

        return TemplateResource::collection($templates);
    }

    public function show(Template $template): TemplateResource
    {
        abort_unless($template->is_active, 404);

        return TemplateResource::make($template);
    }
}
