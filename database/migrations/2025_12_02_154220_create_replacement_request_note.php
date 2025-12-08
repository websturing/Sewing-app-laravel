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
        Schema::create('replacement_request_note', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('replacement_request_id');
            $table->unsignedBigInteger('created_by');
            $table->text('description');
            $table->timestamps();

            // FK
            $table->foreign('replacement_request_id')->references('id')->on('replacement_request');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replacement_request_note');
    }
};
