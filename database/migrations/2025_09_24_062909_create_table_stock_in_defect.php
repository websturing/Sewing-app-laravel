<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ganti anonymous class menjadi named class
class CreateTableStockInDefect extends Migration
{
    public function up(): void
    {
        Schema::create('stock_in_defects', function (Blueprint $table) {
            $table->id();
            $table->integer('qty')->default(0);
            $table->foreignId('stockin_id')->constrained('stock_ins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_in_defects'); // ← Perbaiki juga nama table di sini
    }
}
