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
    Schema::create('fnb_tables', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fnb_outlet_id')->constrained('fnb_outlets')->onDelete('cascade');
      $table->string('name');
      $table->string('location')->nullable();
      $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
      $table->integer('capacity')->default(4);
      $table->timestamps();

      $table->index('fnb_outlet_id');
      $table->index('name');
      $table->index('status');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fnb_tables');
  }
};
