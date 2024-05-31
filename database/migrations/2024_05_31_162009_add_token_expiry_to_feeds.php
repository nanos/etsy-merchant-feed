<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->dateTime('token_expires_at')->nullable();
        });
        DB::table('feeds')
            ->update(['token_expires_at' => now()->toDateTimeString()]);
        Schema::table('feeds', function (Blueprint $table) {
            $table->dateTime('token_expires_at')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
            //
        });
    }
};
