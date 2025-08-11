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

            // Relasi ke tabel items
            $table->foreignId('item_id')
                  ->constrained('items')
                  ->onDelete('cascade');

            // Jenis pergerakan stok: IN (masuk) atau OUT (keluar)
            $table->enum('movement_type', ['IN', 'OUT']);

            // Jumlah stok yang bertambah/berkurang
            $table->integer('qty');

            // Lokasi penyimpanan (opsional, kalau punya lebih dari 1 gudang)
            $table->string('location')->nullable();

            // Path atau nama file bukti foto
            $table->string('photo_path');

            // Catatan alasan pergerakan stok
            $table->text('notes')->nullable();

            // Tanggal action
            $table->timestamp('movement_date')->useCurrent();

            $table->timestamps(); // created_at & updated_at
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
