<?php

namespace App\Livewire\Forms;

use App\Models\Feed;
use App\UpdateFrequencyEnum;
use Illuminate\Validation\Rule;
use Livewire\Form;

class FeedForm extends Form
{
    public Feed $feed;

    public string $google_product_category = '';
    public UpdateFrequencyEnum $update_frequency;
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
        $this->feed->update($this->validate([
            'google_product_category' => 'required',
            'update_frequency' => [
                'required',
                Rule::enum(UpdateFrequencyEnum::class),
            ],
        ]));
    }
}
