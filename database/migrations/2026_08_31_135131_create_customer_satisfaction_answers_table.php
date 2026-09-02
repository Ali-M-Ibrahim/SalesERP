<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_satisfaction_answers', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->foreignUuid('invitation_id')->constrained('customer_satisfaction_invitations')->cascadeOnDelete();

            $table->foreignUuid('question_id')->constrained('customer_satisfaction_questions')->restrictOnDelete();

            $table->unsignedTinyInteger('rating')->nullable();

            $table->text('answer')->nullable();

            $table->timestamps();

            $table->unique(['invitation_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_satisfaction_answers');
    }
};
