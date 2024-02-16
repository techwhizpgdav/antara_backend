<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competition_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('competition_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('team')->default(0);
            $table->string('team_code');
            $table->boolean('allowed')->default(true);
            $table->unsignedInteger('team_size')->default(1);
            $table->unsignedInteger('team_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_user');
    }
};
