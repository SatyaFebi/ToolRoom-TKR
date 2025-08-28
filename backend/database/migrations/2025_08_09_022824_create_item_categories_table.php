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
        Schema::connection('tkr_inventory_management')->create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_type_id')
                  ->constrained('item_types')
                  ->onDelete('cascade'); // jika tipe dihapus, kategori ikut terhapus
            $table->string('name'); // nama kategori
            $table->text('description')->nullable(); // deskripsi opsional
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tkr_inventory_management')->dropIfExists('item_categories');
    }
};
