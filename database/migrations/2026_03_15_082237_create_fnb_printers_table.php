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
        Schema::create('fnb_printers', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('fnb_outlet_id')->constrained('fnb_outlets')->onDelete('cascade');
            $table->string('name');
            $table->enum('connection_type', ['usb', 'network'])->default('usb');
            $table->string('ip_address')->nullable();
            $table->integer('mac_address')->nullable();
            $table->integer('paper_size')->default(80);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index('fnb_outlet_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fnb_printers');
    }
};
