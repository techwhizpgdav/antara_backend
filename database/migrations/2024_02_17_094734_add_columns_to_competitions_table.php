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
        Schema::table('competitions', function (Blueprint $table) {
            $table->boolean('remarks')->default(0);
            $table->boolean('sponsor_task')->default(0);
            $table->string('remarks_label')->nullable();
        });

        Schema::table('competition_user', function (Blueprint $table) {
            $table->string('remarks')->nullable();
            $table->string('sponsor_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            //
        });
    }
};
