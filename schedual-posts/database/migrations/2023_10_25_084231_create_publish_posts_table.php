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
        Schema::create('publish_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->String('account_type');
            $table->String('account_id');
            $table->String('account_name');
            $table->String('status'); // pending or published
            $table->text('thumbnail')->nullable();
            $table->text('link')->nullable();
            $table->string('post_title')->nullable();
            $table->text('content');
            $table->string('youtube_privacy')->nullable(); // public or private
            $table->text('youtube_tags')->nullable();
            $table->unsignedBigInteger('youtube_category')->nullable();
            $table->foreign('youtube_category')->references('id')->on('youtube_categories');
            $table->timestamp('scheduledTime');
            $table->String('tokenApp',1000);
            $table->String('token_secret',1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publish_posts');
    }
};
