<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\CV\Actions\CreateCvAction;
use App\Domain\CV\Actions\DeleteCvAction;
use App\Domain\CV\Actions\UpdateCvAction;
use App\Domain\CV\DTOs\CvData;
use App\Domain\CV\Models\Cv;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCvRequest;
use App\Http\Requests\V1\UpdateCvRequest;
use App\Http\Resources\V1\CvResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CvController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $cvs = QueryBuilder::for(Cv::class)
            ->where('user_id', $request->user()->id)
            ->allowedFilters(
                AllowedFilter::exact('status'),
                'title',
            )
            ->allowedSorts('title', 'created_at', 'updated_at')
            ->defaultSort('-updated_at')
            ->with('template')
            ->paginate($request->integer('per_page', 15));

        return CvResource::collection($cvs);
    }

    public function store(StoreCvRequest $request, CreateCvAction $action): JsonResponse
    {
        $cv = $action->execute(
            $request->user(),
            CvData::fromArray($request->validated()),
        );

        return CvResource::make($cv->load('template'))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Cv $cv): CvResource
    {
        $this->authorize('view', $cv);

        return CvResource::make($cv->load('template'));
    }

    public function update(UpdateCvRequest $request, Cv $cv, UpdateCvAction $action): CvResource
    {
        $cv = $action->execute($cv, CvData::fromArray($request->validated()));

        return CvResource::make($cv->load('template'));
    }

    public function destroy(Request $request, Cv $cv, DeleteCvAction $action): JsonResponse
    {
        $this->authorize('delete', $cv);

        $action->execute($cv);

        return response()->json(null, 204);
    }
}
