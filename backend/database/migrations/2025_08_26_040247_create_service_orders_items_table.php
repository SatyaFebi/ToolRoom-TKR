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
        Schema::connection('tkr_service_management')->create('service_orders_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('service_orders')->onDelete('cascade');
            
            $table->string('service_name');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_taksiran', 12, 2)->default(0);
            $table->decimal('harga_final', 12, 2)->default(0);
            $table->enum('status', ['menunggu', 'dikerjakan', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tkr_service_management')->dropIfExists('service_orders_items');
    }
};
