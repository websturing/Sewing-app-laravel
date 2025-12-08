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
        Schema::create('replacement_request_detail', function (Blueprint $table) {
            $table->id();
            $table->string('gl_no', 191);
            $table->string('size', 10);
            $table->string('color', 255);
            $table->integer('pcs');
            $table->bigInteger('line_id');
            $table->string('description', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replacement_request_detail');
    }
};
