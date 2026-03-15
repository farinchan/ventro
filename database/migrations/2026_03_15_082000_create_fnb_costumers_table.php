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
    Schema::create('fnb_costumers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fnb_outlet_id')->constrained('fnb_outlets')->onDelete('cascade');
      $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
      $table->string('name');
      $table->string('email')->unique()->nullable();
      $table->string('phone')->nullable();
      $table->timestamps();

      $table->index('fnb_outlet_id');
      $table->index('user_id');
      $table->index('email');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fnb_costumers');
  }
};
