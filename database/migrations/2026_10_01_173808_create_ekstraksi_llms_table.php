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
    Schema::create('ekstraksi_llms', function (Blueprint $table) {
        $table->id();

        $table->foreignId('narasi_id')
              ->constrained('narasis')
              ->cascadeOnDelete();

        $table->string('model_llm')->nullable();

        $table->json('hasil_ekstraksi')->nullable();

        $table->enum('status_review', [
            'menunggu',
            'disetujui',
            'ditolak'
        ])->default('menunggu');

        $table->text('catatan_review')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekstraksi_llms');
    }
};
