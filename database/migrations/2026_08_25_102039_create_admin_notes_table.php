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
            Schema::create('admin_notes', function (Blueprint $table) {

                $table->uuid('id')->primary();
                $table->uuidMorphs('noteable');
                $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();
                $table->foreignUuid('sales_rep_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('note');
                $table->boolean('is_important')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                $table->index(['sales_rep_id', 'read_at',]);
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notes');
    }
};
