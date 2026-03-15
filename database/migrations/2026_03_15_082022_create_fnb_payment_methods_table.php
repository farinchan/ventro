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
    Schema::create('fnb_payment_methods', function (Blueprint $table) {
      $table->id();
      $table->foreignUuid('fnb_business_id')->constrained('fnb_businesses')->onDelete('cascade');
      $table->string('name');
      $table->text('description')->nullable();
      $table->enum('type', ['cash', 'card', 'mobile_payment', 'other']);
      $table->timestamps();

      $table->index('fnb_business_id');
      $table->index('name');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fnb_payment_methods');
  }
};
