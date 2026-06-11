<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        // Drop first in case of a partial previous run
        Schema::dropIfExists('user_services');
        Schema::dropIfExists('user_documents');

        // ── user ↔ client_document  (many-to-many) ──
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')
                  ->references('id')
                  ->on('client_documents')
                  ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'document_id']);
        });

        // ── user ↔ service  (many-to-many) ──
        Schema::create('user_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('service_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_services');
        Schema::dropIfExists('user_documents');
    }
};