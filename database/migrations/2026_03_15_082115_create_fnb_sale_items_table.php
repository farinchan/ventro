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
    Schema::create('fnb_sale_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fnb_sale_id')->constrained('fnb_sales')->onDelete('cascade');
      $table->foreignId('fnb_product_variant_id')->nullable()->constrained('fnb_product_variants')->onDelete('set null');
      $table->decimal('unit_price', 10, 2);
      $table->integer('quantity')->default(1);
      $table->decimal('total_price', 10, 2);
      $table->string('note')->nullable();
      $table->timestamps();

      $table->index('fnb_sale_id');
      $table->index('fnb_product_variant_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fnb_sale_items');
  }
};
