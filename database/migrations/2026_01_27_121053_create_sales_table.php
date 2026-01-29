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
       Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Cashier
        $table->string('invoice_number')->unique();
        $table->decimal('total_amount', 12, 2);
        $table->decimal('final_total', 12, 2);
        $table->string('payment_type'); 
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
