<?php

namespace App\Dto;

readonly class ShippingProfileDestinationDto
{
    public function __construct(
        public int $shipping_profile_destination_id,
        public int $shipping_profile_id,
        public string $origin_country_iso,
        public string $destination_country_iso,
        public string $destination_region,
        public PriceDto $primary_cost,
        public PriceDto $secondary_cost,
        public int $shipping_carrier_id,
        public ?string $mail_class = null,
        public ?int $min_delivery_days = null,
        public ?int $max_delivery_days = null,
    )
    {
    }

    public static function make(array $args): self
    {
        $args['primary_cost'] = new PriceDto(...$args['primary_cost']);
        $args['secondary_cost'] = new PriceDto(...$args['secondary_cost']);
        return new self(...$args);
    }

    public function hasDeliveryDays(): bool
    {
        return $this->min_delivery_days !== null && $this->max_delivery_days !== null;
    }

    public function isDomestic(): bool
    {
        return $this->origin_country_iso === $this->destination_country_iso;
    }
}
