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
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial_number', 191);
            $table->integer('ticket_no');
            $table->string('gl_no', 191);
            $table->string('size', 10);
            $table->integer('user_dispatch_id')->nullable();
            $table->string('color', 255);
            $table->integer('pcs');
            $table->date('date_stock_out');
            $table->bigInteger('cor_id');
            $table->bigInteger('user_id');
            $table->string('user_dispatch_name', 191)->nullable();
            $table->string('box_number', 191)->nullable();
            $table->integer('line_id');
            $table->timestamps();
            $table->string('input_source', 255)->nullable();
            $table->string('container_scan_status', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ins');
    }
};
