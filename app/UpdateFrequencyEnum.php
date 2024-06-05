<?php

namespace App;

enum UpdateFrequencyEnum: int
{
    case FIVE_MINUTES = 300;
    case QUARTER_HOUR = 900;
    case THIRTY_MINUTES = 1800;
    case HOURLY = 3600;
    case TWICE_DAILY = 7200;
    case DAILY = 86400;
    case WEEKLY = 604800;
    case MONTHLY = 2419200;
    case ANNUALLY = 29030400;

    public function label(): string
    {
        return match ($this) {
            self::FIVE_MINUTES => 'every 5 min',
            self::QUARTER_HOUR => 'every 15 min',
            self::THIRTY_MINUTES => 'every half hour',
            self::HOURLY => 'hourly',
            self::DAILY => 'daily',
            self::MONTHLY => 'monthly',
            self::TWICE_DAILY => 'twice daily',
            self::WEEKLY => 'weekly',
            self::ANNUALLY => 'annually',
        };
    }
}
