<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('posts_count')->default(0);
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            
            $table->json('tags')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            
            $table->integer('views_count')->default(0);
            $table->integer('read_time')->default(0); // минут
            
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('published_at');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_categories');
    }
};
