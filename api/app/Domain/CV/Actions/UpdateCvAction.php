<?php

declare(strict_types=1);

namespace App\Domain\CV\Actions;

use App\Domain\CV\DTOs\CvData;
use App\Domain\CV\Enums\CvStatus;
use App\Domain\CV\Models\Cv;
use Illuminate\Support\Facades\DB;

final class UpdateCvAction
{
    public function execute(Cv $cv, CvData $data): Cv
    {
        return DB::transaction(function () use ($cv, $data): Cv {
            $wasUnpublished = $cv->status !== CvStatus::Published;

            $cv->update([
                'title' => $data->title,
                'template_id' => $data->templateId,
                'status' => $data->status,
                'data' => $data->data,
                'is_public' => $data->isPublic,
                'published_at' => $wasUnpublished && $data->status === CvStatus::Published
                    ? now()
                    : $cv->published_at,
            ]);

            return $cv->fresh();
        });
    }
}
