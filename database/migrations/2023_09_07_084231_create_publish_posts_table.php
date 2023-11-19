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
            $table->foreignId("creator_id")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->String('account_type');
            $table->String('account_id');
            $table->String('account_name');
            $table->String('status'); // pending or published
            $table->text('thumbnail')->nullable();
            $table->text('link')->nullable();
            $table->string('post_title')->nullable();
            $table->text('content')->nullable();
            $table->string('youtube_privacy')->nullable(); // public or private
            $table->text('youtube_tags')->nullable();
            $table->foreignId("youtube_category")->nullable()->constrained("youtube_categories")->onDelete("cascade")->onUpdate("cascade");
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
