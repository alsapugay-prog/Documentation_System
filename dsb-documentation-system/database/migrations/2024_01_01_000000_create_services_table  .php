<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Para sa Service Name (e.g., Land & Property Titling)
            $table->string('service_type_id');    // Para sa Service Type ID Number (e.g., Service Type ID 1)
            $table->string('primary_contact');    // Para sa Primary Contact name na isusulat mo
            $table->timestamps();                 // Lilikha ng created_at at updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};