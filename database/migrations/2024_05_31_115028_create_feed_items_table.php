<?php

use App\Dto\ListingStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feed_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('feed_id');
            $table->bigInteger('listing_id');
            $table->text('title');
            $table->longText('description');
            $table->enum('state', collect(ListingStateEnum::cases())->pluck('value')->toArray());
            $table->timestamp('created_timestamp');
            $table->timestamp('ending_timestamp');
            $table->integer('quantity');
            $table->text('url');
            $table->text('image_url');
            $table->json('images');
            $table->integer('price_amount');
            $table->integer('price_divisor');
            $table->string('price_currency_code');
            $table->json('data');

            $table->unique(['feed_id', 'listing_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_items');
    }
};
