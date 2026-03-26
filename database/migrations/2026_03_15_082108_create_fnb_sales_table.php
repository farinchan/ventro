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
        Schema::create('fnb_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('fnb_outlet_id')->constrained('fnb_outlets')->onDelete('cascade');
            $table->foreignId('fnb_outlet_staff_id')->nullable()->constrained('fnb_outlet_staff')->onDelete('set null');
            $table->foreignId('fnb_costumer_id')->nullable()->constrained('fnb_costumers')->onDelete('set null');
            $table->foreignId('fnb_payment_method_id')->nullable()->constrained('fnb_payment_methods')->onDelete('set null');
            $table->foreignId('fnb_table_id')->nullable()->constrained('fnb_tables')->onDelete('set null');
            $table->foreignId('fnb_coupon_id')->nullable()->constrained('fnb_coupons')->onDelete('set null');
            $table->string('invoice_number');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->json('taxes')->nullable();
            $table->decimal('total', 10, 2);
            $table->foreignId('fnb_sale_mode_outlet_id')->nullable()->constrained('fnb_sale_mode_outlets')->onDelete('set null');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->index('fnb_outlet_id');
            $table->index('invoice_number');
            $table->index('status');
            $table->index('fnb_sale_mode_outlet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fnb_sales');
    }
};
