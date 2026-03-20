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
            $table->foreignUuid('fnb_outlet_id')->nullable()->constrained('fnb_outlets')->onDelete('set null');
            $table->foreignId('fnb_business_user_id')->nullable()->constrained('fnb_business_users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index('fnb_outlet_id');
            $table->index('fnb_business_user_id');
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
