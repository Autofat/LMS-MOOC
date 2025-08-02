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
    Schema::create('questions', function (Blueprint $table) {
        $table->id(); // Kolom id auto increment
        $table->unsignedBigInteger('material_id')->nullable(); // Kolom untuk relasi ke materials
        $table->text('question'); // Kolom untuk pertanyaan
        $table->json('options'); // Kolom untuk pilihan jawaban, tipe json
        $table->string('answer'); // Kolom untuk jawaban yang benar
        $table->text('explanation')->nullable(); // Kolom penjelasan, bisa kosong
        $table->string('difficulty')->nullable(); // Kolom untuk tingkat kesulitan
        $table->timestamps(); // Kolom created_at dan updated_at
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
