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
        Schema::create('laying_plannings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assignment_line_id')
                ->constrained('assignment_lines')
                ->onDelete('cascade');

            $table->string('color');
            $table->string('type');

            $table->integer('order_qty');
            $table->integer('cut_qty');

            $table->timestamps();

            // enforce uniqueness per line + color
            $table->unique(
                ['assignment_line_id', 'color'],
                'uq_layingplanning_line_color'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laying_plannings');
    }
};
