<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->text('utm_tags')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            $table->dropColumn('utm_tags');
        });
    }
};
