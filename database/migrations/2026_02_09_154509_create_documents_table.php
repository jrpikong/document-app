<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('file_path');
            $table->string('original_name');

            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');

            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('approved_at')->nullable();
            $table->text('approval_note')->nullable();
            $table->integer('sla_hours')->default(24);

            $table->timestamps();

            $table->index(['status', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
