<?php

namespace App\Dto;

readonly class EtsyShopDto
{
    public function __construct(
        public int $shop_id,
        public string $shop_name,
        public int $user_id,
        public string $title,
        public string $currency_code,
        public string $url,
    )
    {
    }
}
