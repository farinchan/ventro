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
        Schema::create('fnb_outlet_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fnb_outlet_id')->constrained('fnb_outlets')->onDelete('cascade');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['cashier', 'manager', 'staff']);
            $table->timestamps();
            $table->softDeletes();

              $table->index('fnb_outlet_id');
              $table->index('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fnb_outlet_staff');
    }
};
