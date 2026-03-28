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
        Schema::create('fnb_midtrans', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('fnb_business_id')->constrained('fnb_businesses')->onDelete('cascade');
            $table->string('merchant_id');
            $table->text('client_key');
            $table->text('server_key');
            $table->text('snap_url');
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fnb_midtrans');
    }
};
