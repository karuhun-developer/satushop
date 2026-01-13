<?php

namespace App\Enums;

enum PaymentStatusEnum: int
{
    case PENDING = 0;
    case CAPTURED = 1;
    case SETTLEMENT = 2;
    case DENY = -1;
    case EXPIRED = -2;
    case CANCEL = -3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CAPTURED => 'Captured',
            self::SETTLEMENT => 'Settlement',
            self::DENY => 'Denied',
            self::EXPIRED => 'Expired',
            self::CANCEL => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::CAPTURED => 'green',
            self::SETTLEMENT => 'green',
            self::DENY => 'red',
            self::EXPIRED => 'red',
            self::CANCEL => 'red',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
