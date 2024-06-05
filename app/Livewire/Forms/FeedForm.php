<?php

namespace App\Livewire\Forms;

use App\Models\Feed;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FeedForm extends Form
{
    public Feed $feed;

    #[Validate('required')]
    public string $google_product_category = '';
    #[Validate('required')]
    public int $update_frequency;
    public ?string $brand_name = null;

    public function setFeed(Feed $feed): void
    {
        $this->feed = $feed;
        $this->google_product_category = $feed->google_product_category ?? '';
        $this->brand_name = $feed->brand_name;
        $this->update_frequency = $feed->update_frequency;
    }

    public function update(): void
    {
        $this->validate();

        $this->feed->update($this->only([
            'google_product_category',
            'update_frequency',
            'brand_name'
        ]));
    }
}
