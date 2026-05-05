<?php

declare(strict_types=1);

namespace App\Domain\CV\Actions;

use App\Domain\CV\Models\Cv;

final class DeleteCvAction
{
    public function execute(Cv $cv): void
    {
        $cv->delete();
    }
}
