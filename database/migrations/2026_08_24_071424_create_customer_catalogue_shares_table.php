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
        Schema::create('customer_catalogue_shares', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('resource_id')->constrained('resources')->restrictOnDelete();
            $table->foreignUuid('shared_by')->constrained('users')->restrictOnDelete();
            $table->enum('method', ['whatsapp', 'email', 'link', 'in_person', 'other'])->nullable();
            $table->timestamp('shared_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['customer_id', 'shared_at']);
            $table->index(['resource_id', 'shared_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_catalogue_shares');
    }
};
