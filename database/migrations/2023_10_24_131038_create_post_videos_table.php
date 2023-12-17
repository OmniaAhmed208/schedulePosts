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
        Schema::create('post_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId("post_id")->nullable()->constrained("publish_posts")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("creator_id")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->text('video');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_videos');
    }
};
