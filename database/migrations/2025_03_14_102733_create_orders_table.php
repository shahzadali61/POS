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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_id')->constrained('purchase_products')->onDelete('cascade');
            $table->foreignId('sale_id')->constrained('sale_products')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('sale_price', 10, 2);
            $table->decimal('total_amount', 10, 2)->nullable(); 
            $table->decimal('discount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
