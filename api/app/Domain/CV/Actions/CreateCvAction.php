<?php

declare(strict_types=1);

namespace App\Domain\CV\Actions;

use App\Domain\CV\DTOs\CvData;
use App\Domain\CV\Enums\CvStatus;
use App\Domain\CV\Models\Cv;
use App\Domain\Identity\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CreateCvAction
{
    public function execute(User $user, CvData $data): Cv
    {
        return DB::transaction(function () use ($user, $data): Cv {
            $cv = new Cv;
            $cv->user_id = $user->id;
            $cv->template_id = $data->templateId;
            $cv->title = $data->title;
            $cv->slug = $this->uniqueSlug($data->title);
            $cv->status = $data->status;
            $cv->data = $data->data;
            $cv->is_public = $data->isPublic;
            $cv->published_at = $data->status === CvStatus::Published ? now() : null;
            $cv->save();

            return $cv;
        });
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $i = 1;

        while (Cv::query()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . ++$i;
        }

        return $slug;
    }
}
