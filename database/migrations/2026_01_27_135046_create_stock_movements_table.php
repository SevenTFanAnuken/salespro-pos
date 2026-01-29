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
    Schema::create('stock_movements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained(); // Who did the action
        $table->integer('quantity'); // Positive for 'In', Negative for 'Out/Broken'
        $table->enum('type', ['in', 'out', 'transfer', 'broken']);
        $table->text('notes')->nullable(); // e.g., "Mug dropped by staff"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
