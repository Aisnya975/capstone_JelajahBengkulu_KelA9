<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pengetahuans', function (Blueprint $table) {
        // Tambahkan kolom image setelah kolom 'name' agar rapi
        $table->string('image')->nullable()->after('name'); 
    });
}

public function down()
{
    Schema::table('pengetahuans', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}
};
