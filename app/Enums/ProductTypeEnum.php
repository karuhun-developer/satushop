<?php

namespace App\Enums;

enum ProductTypeEnum: string
{
    case SIMPLE = 'simple';
    case VARIABLE = 'variable';
    case BUNDLE = 'bundle';

    public function label(): string
    {
        return match ($this) {
            self::SIMPLE => 'simple',
            self::VARIABLE => 'Variable',
            self::BUNDLE => 'Bundle',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SIMPLE => 'blue',
            self::VARIABLE => 'green',
            self::BUNDLE => 'orange',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
