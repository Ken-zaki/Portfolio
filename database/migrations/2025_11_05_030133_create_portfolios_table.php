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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Information
            $table->string('full_name');
            $table->string('title')->default('Full Stack Developer');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('location')->default('San Pascual, Batangas');
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            
            // Hero Section
            $table->string('greeting')->default('Hello, I\'m');
            $table->text('hero_description')->nullable();
            
            // About Section
            $table->text('about_content')->nullable();
            $table->integer('projects_completed')->default(50);
            $table->integer('years_experience')->default(3);
            $table->integer('happy_clients')->default(20);
            $table->integer('satisfaction_rate')->default(100);
            
            // Skills (stored as JSON)
            $table->json('frontend_skills')->nullable();
            $table->json('backend_skills')->nullable();
            $table->json('tools_skills')->nullable();
            $table->json('technologies')->nullable();
            
            // Education (stored as JSON array)
            $table->json('education')->nullable();
            
            // Projects (stored as JSON array)
            $table->json('projects')->nullable();
            
            // Social Links
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            
            // Visibility
            $table->boolean('is_public')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
