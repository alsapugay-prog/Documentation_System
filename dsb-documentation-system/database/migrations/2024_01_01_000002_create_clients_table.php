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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            
            // Siguraduhing 'services' ang pangalan ng table mo at 'id' ang primary key nito
            $table->foreignId('service_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('client_name');
            $table->date('date_received');

            // Maganda ang pagkakagawa ng enum at default value nito!
            $table->enum('status', [
                'Pending',
                'On going',
                'Completed'
            ])->default('Pending');

            // Nilagyan natin ng ->default('[]') para hindi maging null at laging handa bilang array sa Alpine.js mo
            $table->json('requirements_checklist')->default('[]');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};