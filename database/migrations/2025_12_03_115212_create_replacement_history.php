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
        Schema::create('replacement_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('replacement_request_id');
            $table->unsignedBigInteger('workflow_step_id');
            $table->unsignedBigInteger('action_by');
            $table->text('note');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            // FK
            $table->foreign('replacement_request_id')->references('id')->on('replacement_request');
            $table->foreign('workflow_step_id')->references('id')->on('workflow_steps');
            $table->foreign('action_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void

    {
        Schema::dropIfExists('replacement_histories');
    }
};
