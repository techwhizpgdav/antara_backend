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
            $table->text('description')->after('title');
            $table->integer('minimum_size', false, true)->after('rules');
            $table->integer('maximum_size', false, true)->after('minimum_size');
            $table->json('images')->nullable()->after('description');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('otp')->nullable()->after('password');
            $table->timestamp('otp_created_at')->nullable()->after('otp');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
