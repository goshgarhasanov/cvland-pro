<?php

declare(strict_types=1);

namespace App\Domain\Billing\Models;

use App\Domain\Billing\Enums\OrderStatus;
use App\Domain\Catalog\Models\Template;
use App\Domain\CV\Models\Cv;
use App\Domain\Identity\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'reference',
    'user_id',
    'template_id',
    'cv_id',
    'status',
    'amount_minor',
    'currency',
    'payment_method',
    'metadata',
    'paid_at',
])]
class Order extends Model
{
    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'amount_minor' => 'integer',
            'metadata' => 'array',
            'paid_at' => 'datetime',
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

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
