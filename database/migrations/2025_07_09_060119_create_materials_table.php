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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul materi
            $table->text('description')->nullable(); // Deskripsi materi
            $table->string('file_path'); // Path file PDF
            $table->string('file_name'); // Nama file asli
            $table->integer('file_size'); // Ukuran file dalam bytes
            $table->string('category')->nullable(); // Kategori materi
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
