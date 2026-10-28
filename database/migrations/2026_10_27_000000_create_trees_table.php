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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->string('id_pohon')->unique()->comment('Kode unik pohon, misal: SMKKN-001');
            $table->string('nama_pohon');
            $table->string('nama_latin')->nullable();
            $table->decimal('diameter', 6, 2)->nullable()->comment('Diameter batang pohon dalam cm');
            $table->decimal('tinggi', 6, 2)->nullable()->comment('Tinggi pohon dalam meter');
            $table->string('tingkat')->nullable()->comment('Kategori tingkat pertumbuhan: Pancang, Tiang, Pohon');
            $table->decimal('berat_jenis', 6, 3)->nullable()->comment('Berat jenis kayu');
            $table->decimal('biomassa_agb', 10, 2)->nullable()->comment('Biomassa Atas Tanah (Above Ground Biomass) dalam kg');
            $table->decimal('cadangan_karbon', 10, 3)->nullable()->comment('Cadangan Karbon dalam Ton C');
            $table->decimal('estimasi_karbon', 10, 2)->default(0)->comment('Estimasi serapan karbon dalam kg CO2e');
            $table->date('tanggal_tanam')->nullable();
            $table->decimal('kordinat_lat', 10, 7)->nullable();
            $table->decimal('kordinat_lng', 10, 7)->nullable();
            $table->string('foto')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('lokasi_taman')->nullable()->comment('Area / Kawasan Hutan Arboretum SMKKN');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};
