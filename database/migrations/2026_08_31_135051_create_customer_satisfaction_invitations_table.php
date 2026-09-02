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
        Schema::create('customer_satisfaction_invitations', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->foreignUuid('visit_id')->constrained('visits')->cascadeOnDelete();

            $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->foreignUuid('sales_rep_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('email');

            $table->string('token', 64)->unique();

            $table->timestamp('sent_at')->nullable();

            $table->timestamp('opened_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->unique('visit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_satisfaction_invitations');
    }
};
