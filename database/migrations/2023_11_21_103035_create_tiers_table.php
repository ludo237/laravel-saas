<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->index();
            $table->unsignedBigInteger('price')->default(0);
            $table->string('stripe_price_key')->nullable()->default(null);
            $table->string('stripe_price_id')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiers');
    }
};
