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
        Schema::create('visits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('sales_rep_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('visit_purpose_id')->nullable()->constrained('visit_purposes')->nullOnDelete();
            $table->string('purpose_other')->nullable();
            $table->timestamp('scheduled_at');

            $table->string('contact_point')->nullable();
            $table->string('contact_point_position')->nullable();

            /*
             * Check in
             */
            $table->timestamp('check_in_at')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->decimal('check_in_accuracy', 10, 2)->nullable();

            /*
             * Check out
             */
            $table->timestamp('check_out_at')->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->decimal('check_out_accuracy', 10, 2)->nullable();

            $table->enum('status', ['scheduled', 'checked_in', 'completed', 'cancelled', 'missed'])->default('scheduled');

            $table->text('visit_notes')->nullable();
            $table->text('client_requests')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['sales_rep_id', 'scheduled_at']);
            $table->index(['customer_id', 'scheduled_at']);
            $table->index('status');




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
