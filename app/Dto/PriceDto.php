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

    public function __toString(): string
    {
        return $this->currency_code . ' ' .round($this->amount / $this->divisor, 2);
    }
}
