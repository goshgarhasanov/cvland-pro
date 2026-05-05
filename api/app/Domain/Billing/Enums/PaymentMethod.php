<?php

declare(strict_types=1);

namespace App\Domain\Billing\Enums;

enum PaymentMethod: string
{
    case Stripe = 'stripe';
    case Balance = 'balance';
    case Manual = 'manual';
}
