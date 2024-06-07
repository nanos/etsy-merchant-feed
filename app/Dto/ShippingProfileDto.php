<?php

namespace App\Dto;

/**
 * @property ShippingProfileDestinationDto[] $shipping_profile_destinations
 */
readonly class ShippingProfileDto
{
    public function __construct(
        public int $shipping_profile_id,
        public string $title,
        public int $user_id,
        public string $processing_days_display_label,
        public string $origin_country_iso,
        public string $origin_postal_code,
        public string $profile_type,
        public bool $is_deleted,
        public int $domestic_handling_fee,
        public int $international_handling_fee,
        public array $shipping_profile_destinations = [],
        public array $shipping_profile_upgrades = [],
        public ?int $min_processing_days = null,
        public ?int $max_processing_days = null,
    )
    {
    }

    public static function make(array $args): self
    {
        if(isset($args['shipping_profile_destinations'])) {
            $args['shipping_profile_destinations'] = array_map(fn(array $destination) => ShippingProfileDestinationDto::make($destination), $args['shipping_profile_destinations']);
        }
        return new self(...$args);
    }

    public function hasProcessingDays(): bool
    {
        return $this->min_processing_days !== null && $this->max_processing_days !== null;
    }
}
