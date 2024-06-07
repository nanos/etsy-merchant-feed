<?php

namespace App\Dto;

readonly class EtsyListingDto
{
    public function __construct(
        public int $listing_id,
        public int $user_id,
        public int $shop_id,
        public string $title,
        public string $description,
        public ListingStateEnum $state,
        public int $creation_timestamp,
        public int $created_timestamp,
        public int $ending_timestamp,
        public int $original_creation_timestamp,
        public int $last_modified_timestamp,
        public int $updated_timestamp,
        public int $state_timestamp,
        public int $quantity,
        public int $shop_section_id,
        public int $featured_rank,
        public string $url,
        public int $num_favorers,
        public bool $non_taxable,
        public bool $is_taxable,
        public bool $is_customizable,
        public bool $is_personalizable,
        public bool $personalization_is_required,
        public string $listing_type,
        public int $shipping_profile_id,
        public int $processing_min,
        public int $processing_max,
        public string $who_made,
        public string $when_made,
        public bool $is_supply,
        public bool $is_private,
        public string $file_data,
        public bool $has_variations,
        public bool $should_auto_renew,
        public string $language,
        public PriceDto $price,
        public int $taxonomy_id,
        public ShippingProfileDto $shipping_profile,
        public int $views = 0,
        public ?int $personalization_char_count_max = null,
        public ?string $personalization_instructions = null,
        public ?int $return_policy_id = null,
        public ?int $item_weight = null,
        public ?string $item_weight_unit = null,
        public ?int $item_length = null,
        public ?int $item_width = null,
        public ?int $item_height = null,
        public ?string $item_dimensions_unit = null,
        public ?array $tags = [],
        public ?array $materials = [],
        public ?array $style = [],
        public ?array $strings = [],
        public ?array $user = [],
        public ?array $shop = [],
        public ?array $images = [],
        public ?array $videos = [],
        public ?array $inventory = [],
        public ?array $production_partners = [],
        public ?array $skus = [],
        public ?array $translations = [],
        public ?array $shipping_profile_destinations = [],

    )
    {
    }

    public static function make(array $args): self
    {
        if(is_string($args['state'])) {
            $args['state'] = ListingStateEnum::from($args['state']);
        }
        $args['price'] = new PriceDto(...$args['price']);
        if(isset($args['shipping_profile'])) {
            $args['shipping_profile'] = ShippingProfileDto::make($args['shipping_profile']);
        }
        return new self(... $args);
    }
}
