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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('max_transactions_per_day')->nullable();
            $table->integer('max_outlets')->nullable();
            $table->integer('max_users')->nullable();
            $table->string('status')->default('active');
            $table->decimal('price', 10, 2)->default(0.00);

            $table->index('name');
            $table->index('max_transactions_per_day');
            $table->index('max_outlets');
            $table->index('max_users');
            $table->index('status');
            $table->index('price');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
