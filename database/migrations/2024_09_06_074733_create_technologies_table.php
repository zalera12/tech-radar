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
        Schema::create('technologies', function (Blueprint $table) {
            // Kolom 'id' dengan tipe CHAR(26) dan dijadikan primary key
            $table->ulid('id')->primary();

            // Kolom 'company_id' dengan tipe CHAR(26) dan foreign key ke tabel companies
            $table->char('company_id', 26);
            
            // Kolom 'category_id' dengan tipe CHAR(26) dan foreign key ke tabel categories
            $table->char('category_id', 26);
            
            // Kolom 'user_id' dengan tipe CHAR(26) dan foreign key ke tabel users
            $table->char('user_id', 26);
            
            // Kolom 'name' dengan tipe VARCHAR(100) yang wajib diisi
            $table->string('name', 100);
            
            // Kolom 'description' dengan tipe LONGTEXT (untuk string panjang)
            $table->longText('description')->nullable();

            // Kolom 'is_new' dengan tipe BOOLEAN, defaultnya FALSE
            $table->boolean('is_new')->default(false);
            
            // Kolom 'quadrant' dengan tipe ENUM dengan opsi tertentu yang wajib diisi
            $table->enum('quadrant', ['Techniques', 'Platforms', 'Tools', 'Language and Framework']);

            // Kolom 'ring' dengan tipe ENUM dengan opsi tertentu yang wajib diisi
            $table->enum('ring', ['hold', 'adopt', 'assess', 'trial']);

            // Kolom 'created_at' dan 'updated_at' dengan timestamp
            $table->timestamps();

            // Definisikan foreign key pada kolom 'company_id'
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Definisikan foreign key pada kolom 'category_id'
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            // Definisikan foreign key pada kolom 'user_id'
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technologies');
    }
};
