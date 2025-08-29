<?php

declare(strict_types=1);

use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->constrained('teams')->restrictOnDelete();
            $table->foreignUlid('tier_id')->constrained('tiers')->restrictOnDelete();
            $table->string('stripe_subscription_id')->nullable()->default(null);
            /**
             * Add whatever your subscription might support
             * Also edit
             *
             * @see Subscription
             * @see SubscriptionResource
             */
            $table->timestamp('expires_at');
            $table->timestamp('cancelled_at')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
