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
        Schema::connection('tkr_service_management')->create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id');

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->enum('jenis_kendaraan',  ['Mobil', 'Motor']);
            $table->string('merek');
            $table->string('model')->nullable();
            $table->integer('tahun_produksi')->nullable();
            $table->string('no_polisi')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tkr_service_management')->dropIfExists('vehicles');
    }
};
