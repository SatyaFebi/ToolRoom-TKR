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
        Schema::connection('tkr_service_management')->create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');

            $table->text('keluhan_pelanggan');
            $table->decimal('taksiran_biaya', 12, 2)->nullable();
            $table->string('estimasi')->nullable();
            $table->date('tanggal_masuk');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['menunggu', 'dikerjakan', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->enum('pembayaran', ['cash', 'credit_card', 'debit_card', 'e-wallet', 'qris', 'transfer'])->nullable();
            $table->enum('penggantian_part_material', ['langsung', 'izin'])->nullable();
            $table->text('catatan_service')->nullable();
            $table->decimal('total_biaya_akhir', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tkr_service_management')->dropIfExists('service_orders');
    }
};
