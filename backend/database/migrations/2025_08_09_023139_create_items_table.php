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
        Schema::connection('tkr_inventory_management')->create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_category_id')
                  ->constrained('item_categories')
                  ->onDelete('cascade'); // hapus barang jika kategori dihapus

            $table->string('code')->unique(); // kode barang unik, misal TL-001
            $table->string('name'); // nama barang
            $table->string('brand')->nullable(); // merek barang (opsional)
            $table->string('unit'); // satuan (pcs, liter, set, dll)
            $table->integer('stock')->default(0); // jumlah stok saat ini
            $table->integer('min_stock')->default(0); // batas stok minimum
            $table->decimal('price', 15, 2)->default(0); // harga barang

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tkr_inventory_management')->dropIfExists('items');
    }
};
