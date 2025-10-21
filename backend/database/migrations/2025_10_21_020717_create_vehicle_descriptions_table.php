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
        Schema::connection('tkr_service_management')->create('vehicle_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->string('sejak_kapan');
            $table->enum('frekuensi', ['selalu', 'kadang-kadang', 'jarang'])->nullable();
            $table->string('dimana')->nullable();
            $table->integer('kecepatan')->nullable();
            $table->boolean('lampu_indikator')->default(false)->nullable();
            $table->integer('odometer')->nullable();
            $table->enum('fuel_level', ['empty', '1/4', '1/2', '3/4', 'full'])->nullable();
            $table->enum('stnk', ['ada', 'tidak_ada']);
            $table->enum('buku_servis', ['ada', 'tidak_ada']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_descriptions');
    }
};
