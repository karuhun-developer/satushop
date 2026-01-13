<?php

namespace App\Enums;

enum ShippingStatusEnum: int
{
    case PENDING = 0;
    case SHIPPED = 1;
    case DELIVERED = 2;
    case RETURNED = 3;
    case CANCELLED = 4;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::RETURNED => 'Returned',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::SHIPPED => 'blue',
            self::DELIVERED => 'green',
            self::RETURNED => 'red',
            self::CANCELLED => 'red',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
