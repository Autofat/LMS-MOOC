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
        $table->text('question');
        $table->string('tipe_soal')->default('pilihan_ganda'); // Kolom untuk tipe soal
        $table->text('option_a'); // Kolom untuk opsi A
        $table->text('option_b'); // Kolom untuk opsi B
        $table->text('option_c'); // Kolom untuk opsi C, bisa kosong
        $table->text('option_d'); // Kolom untuk opsi D, bisa kosong
        $table->text('option_e');
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
