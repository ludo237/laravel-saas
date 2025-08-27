<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_roles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name')->index();
            $table->json('permissions');
            $table->timestamps();
        });

        Schema::create('teams_members', function (Blueprint $table) {
            $table->foreignUlid('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('role_id')->constrained('team_roles')->restrictOnDelete();
            $table->boolean('default')->default(false);
            $table->timestamp('joined_at');
            $table->primary(['team_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_roles');
        Schema::dropIfExists('teams_members');
    }
};
