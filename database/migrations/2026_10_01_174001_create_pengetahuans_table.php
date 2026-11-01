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
    Schema::create('pengetahuans', function (Blueprint $table) {
        $table->id();

        $table->foreignId('narasi_id')
              ->constrained('narasis')
              ->cascadeOnDelete();

        $table->foreignId('ekstraksi_llm_id')
              ->nullable()
              ->constrained('ekstraksi_llms')
              ->nullOnDelete();

        $table->string('name');
        $table->string('category');
        $table->text('description')->nullable();
        $table->text('practice')->nullable();
        $table->string('cultural_term')->nullable();
        $table->string('community')->nullable();
        $table->string('people')->nullable();
        $table->string('location')->nullable();
        $table->string('time')->nullable();
        $table->text('meaning')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengetahuans');
    }
};
