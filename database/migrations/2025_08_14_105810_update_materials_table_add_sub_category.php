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
        Schema::table('materials', function (Blueprint $table) {
            // Tambah kolom sub_category_id
            $table->unsignedBigInteger('sub_category_id')->nullable()->after('category');
            
            // Foreign key constraint
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('set null');
            
            // Index untuk performa
            $table->index('sub_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // Drop foreign key dan kolom
            $table->dropForeign(['sub_category_id']);
            $table->dropColumn('sub_category_id');
        });
    }
};
