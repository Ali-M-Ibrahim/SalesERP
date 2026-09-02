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
        Schema::create('customer_satisfaction_settings', function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->boolean('is_enabled')->default(true);

            /*
             * Delay after completed visit.
             */
            $table->unsignedInteger('send_after_minutes')->default(60);

            /*
             * Prevent sending surveys too frequently
             * to the same customer.
             */
            $table->unsignedInteger('minimum_days_between_surveys')->default(7);
            $table->unsignedInteger('survey_expiry_days')->default(14);

            /*
             * Email settings
             */
            $table->string('email_subject')->default('Thank you for your time');
            $table->text('email_intro')->nullable();
            $table->boolean('include_visit_summary')->default(true);
            $table->boolean('include_samples')->default(true);
            /*
             * Notify admin when rating <= threshold.
             */
            $table->unsignedTinyInteger('low_rating_threshold')->default(2);
            $table->boolean('notify_on_low_rating')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_satisfaction_settings');
    }
};
