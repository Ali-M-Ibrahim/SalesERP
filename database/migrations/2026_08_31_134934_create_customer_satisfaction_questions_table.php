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
        Schema::create('customer_satisfaction_questions', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->string('question');

            /*
             * rating
             * text
             * textarea
             */
            $table->string('type')->default('rating');

            /*
             * salesman
             * product
             * meeting
             * service
             * general
             */
            $table->string('category')->nullable();

            $table->boolean('is_required')->default(true);

            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_satisfaction_questions');
    }
};
