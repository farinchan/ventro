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
        Schema::create('fnb_sale_mode_outlets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fnb_sale_mode_id')->constrained('fnb_sale_modes')->onDelete('cascade');
            $table->foreignId('fnb_outlet_id')->constrained('fnb_outlets')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['fnb_sale_mode_id', 'fnb_outlet_id']);
            $table->index('fnb_sale_mode_id');
            $table->index('fnb_outlet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fnb_sale_mode_outlets');
    }
};
