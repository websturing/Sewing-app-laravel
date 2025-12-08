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
        Schema::create('replacement_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_definition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('current_step_id')->nullable()->constrained('workflow_steps')->nullOnDelete();
            $table->string('serial_number')->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed', 'rejected'])->default('draft');
            $table->timestamps();


            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replacement_request');
    }
};
