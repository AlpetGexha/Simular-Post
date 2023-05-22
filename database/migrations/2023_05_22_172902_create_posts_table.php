<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->text('post_text')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_text')->nullable();
            $table->foreignId('post_id')->nullable()->references('id')->on('posts');

            $table->unsignedBigInteger('post_reactions_count')->default(0);
            $table->unsignedBigInteger('comments_count')->default(0);
            $table->unsignedBigInteger('shared_post_count')->default(0);
            $table->unsignedBigInteger('media_count')->default(0);
            $table->unsignedBigInteger('post_comments_reactions_count')->default(0);
            $table->unsignedBigInteger('reports_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
