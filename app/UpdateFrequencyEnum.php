<?php

namespace App;

enum UpdateFrequencyEnum: int
{
    case FIVE_MINUTES = 300;
    case QUARTER_HOUR = 900;
    case THIRTY_MINUTES = 1800;
    case HOURLY = 3600;
    case DAILY = 86400;
    case MONTHLY = 2419200;

    public function label(): string
    {
        return match ($this) {
            self::FIVE_MINUTES => 'every 5 min',
            self::QUARTER_HOUR => 'every 15 min',
            self::THIRTY_MINUTES => 'every half hour',
            self::HOURLY => 'hourly',
            self::DAILY => 'daily',
            self::MONTHLY => 'monthly',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn(UpdateFrequencyEnum $case) => [
                $case->value => $case->label()
            ],
            self::cases(),
        );
    }

}
