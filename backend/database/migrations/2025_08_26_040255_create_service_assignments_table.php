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
        Schema::connection('tkr_service_management')->create('service_assignments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('murid_id');

            $table->foreign('order_id')->references('id')->on('service_orders')->onDelete('cascade');
            $table->enum('peran_murid', ['Mekanik Utama', 'Asisten', 'Pemeriksa'])->nullable();
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tkr_service_management')->dropIfExists('service_assignments');
    }
};
