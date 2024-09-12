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
        Schema::create('categories', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Kolom 'company_id' dengan tipe CHAR(26) dan foreign key ke tabel companies
            $table->char('company_id', 26);
            
            // Kolom 'name' dengan tipe VARCHAR(100) yang wajib diisi
            $table->string('name', 100);
            
            // Kolom 'description' dengan tipe LONGTEXT (untuk string panjang)
            $table->longText('description')->nullable();

            // Kolom 'created_at' dan 'updated_at' dengan timestamp
            $table->timestamps();

            // Definisikan foreign key pada kolom 'company_id'
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
