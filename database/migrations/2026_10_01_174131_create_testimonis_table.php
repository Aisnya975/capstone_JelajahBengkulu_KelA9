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
    Schema::create('testimonis', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('pekerjaan')->nullable(); // Contoh: Peneliti Budaya, Mahasiswa, Tokoh Masyarakat
        $table->text('pesan');
        $table->unsignedTinyInteger('rating')->default(5); // Skala 1 - 5
        $table->boolean('is_active')->default(true); // Status tampil atau disembunyikan
        $table->timestamps();
    });
}
};
