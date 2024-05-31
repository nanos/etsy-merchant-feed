<?php

namespace App\Dto;

readonly class PriceDto
{
    public function __construct(
        public int $amount,
        public int $divisor,
        public string $currency_code,
    )
    {
    }
}
