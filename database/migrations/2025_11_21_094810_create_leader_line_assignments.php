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
        Schema::create('leader_line_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // leader from users
            $table->unsignedBigInteger('line_id'); // reference to lines
            $table->timestamp('assigned_at')->nullable()->default(now());
            $table->timestamp('unassigned_at')->nullable(); // null = active
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('line_id');
            $table->index(['user_id', 'is_active']);

            // Unique active assignment
            $table->unique(['user_id', 'line_id', 'unassigned_at']);

            // FK
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('line_id')->references('id')->on('lines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leader_line_assignments');
    }
};
