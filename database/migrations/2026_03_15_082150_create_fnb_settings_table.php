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
    Schema::create('fnb_settings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fnb_outlet_id')->constrained('fnb_outlets')->onDelete('cascade');
      $table->string('key');
      $table->text('value')->nullable();
      $table->timestamps();

      $table->index('fnb_outlet_id');
      $table->index('key');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fnb_settings');
  }
};
