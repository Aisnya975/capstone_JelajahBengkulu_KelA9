<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonis', function (Blueprint $table) {
            // Menambahkan kolom yang mungkin belum ada di database
            if (!Schema::hasColumn('testimonis', 'nama')) {
                $table->string('nama')->after('id');
            }
            if (!Schema::hasColumn('testimonis', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('testimonis', 'pesan')) {
                $table->text('pesan')->after('pekerjaan');
            }
            if (!Schema::hasColumn('testimonis', 'rating')) {
                $table->unsignedTinyInteger('rating')->default(5)->after('pesan');
            }
            if (!Schema::hasColumn('testimonis', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('rating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('testimonis', function (Blueprint $table) {
            $table->dropColumn(['nama', 'pekerjaan', 'pesan', 'rating', 'is_active']);
        });
    }
};