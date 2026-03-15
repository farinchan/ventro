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
    Schema::create('fnb_coupons', function (Blueprint $table) {
      $table->id();
      $table->foreignUuid('fnb_business_id')->constrained('fnb_businesses')->onDelete('cascade');
      $table->string('code')->unique();
      $table->text('description')->nullable();
      $table->enum('type', ['percentage', 'fixed_amount'])->default('percentage');
      $table->decimal('value', 10, 2);
      $table->integer('usage_limit')->nullable();
      $table->integer('used_count')->default(0);
      $table->dateTime('valid_from')->nullable();
      $table->dateTime('valid_until')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();

      $table->index('fnb_business_id');
      $table->index('code');
      $table->index('is_active');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fnb_coupons');
  }
};
