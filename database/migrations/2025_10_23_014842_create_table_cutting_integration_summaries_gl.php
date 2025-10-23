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
        // Level 1: GL Summary
        Schema::create('cutting_gl_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('gl_number', 50)->unique();
            $table->integer('order_qty')->default(0);
            $table->integer('cut_qty')->default(0);
            $table->integer('stock_out_qty')->default(0);
            $table->integer('replacement_qty')->default(0);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });

        // Level 2: Color per GL
        Schema::create('cutting_gl_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cutting_gl_summary_id')->constrained('cutting_gl_summaries')->onDelete('cascade');
            $table->string('color', 255);
            $table->string('type', 50)->nullable();
            $table->integer('order_qty')->default(0);
            $table->integer('cut_qty')->default(0);
            $table->integer('stock_out_qty')->default(0);
            $table->integer('replacement_qty')->default(0);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();

            $table->index(['cutting_gl_summary_id', 'color']);
        });

        // Level 3: Size per Color
        Schema::create('cutting_gl_color_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cutting_gl_color_id')->constrained('cutting_gl_colors')->onDelete('cascade');
            $table->string('size', 50);
            $table->integer('order_qty')->default(0);
            $table->integer('cut_qty')->default(0);
            $table->integer('stock_out_qty')->default(0);
            $table->integer('replacement_qty')->default(0);
            $table->timestamps();

            $table->index(['cutting_gl_color_id', 'size']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutting_gl_color_sizes');
        Schema::dropIfExists('cutting_gl_colors');
        Schema::dropIfExists('cutting_gl_summaries');
    }
};
