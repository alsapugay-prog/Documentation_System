<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the user_profiles table for storing extended profile data
     * linked one-to-one with the users table.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            // One-to-one relationship: each user has one profile
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');  // Delete profile when user is deleted

            // Optional extended profile fields
            $table->string('avatar')->nullable();         // Stored path to uploaded profile picture
            $table->string('contact_number')->nullable(); // Phone / mobile number
            $table->text('address')->nullable();          // Full address
            $table->text('bio')->nullable();              // About Me / Bio section
            $table->string('position')->nullable();       // Job title / position
            $table->string('department')->nullable();     // Department within org
            $table->date('birthdate')->nullable();        // Optional birthdate
            $table->string('gender')->nullable();         // Optional gender
            $table->string('facebook')->nullable();       // Social: Facebook URL
            $table->string('linkedin')->nullable();       // Social: LinkedIn URL

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};