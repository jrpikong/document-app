<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legacy_document_maps', function (Blueprint $table) {
            $table->id();
            $table->string('source', 50)->default('wordpress');
            $table->string('legacy_table', 100);
            $table->unsignedBigInteger('legacy_id');
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->string('legacy_file_url')->nullable();
            $table->json('legacy_payload')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();

            $table->unique(['source', 'legacy_table', 'legacy_id'], 'legacy_source_table_id_unique');
            $table->index('document_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legacy_document_maps');
    }
};
