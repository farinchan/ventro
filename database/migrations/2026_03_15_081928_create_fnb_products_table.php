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
        Schema::create('fnb_products', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('fnb_business_id')->constrained('fnb_businesses')->onDelete('cascade');
            $table->foreignId('fnb_product_category_id')->nullable()->constrained('fnb_product_categories')->onDelete('set null');
            $table->string('image')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('fnb_business_id');
            $table->index('fnb_product_category_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fnb_products');
    }
};
