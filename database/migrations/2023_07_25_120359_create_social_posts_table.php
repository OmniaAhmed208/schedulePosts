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
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_account_id');
            $table->foreign('api_account_id')->references('id')->on('apis');
            $table->string('post_id');
            $table->text('post_img')->nullable();
            $table->text('post_video')->nullable();
            $table->text('post_link');
            $table->string('post_title')->nullable();
            $table->text('content')->nullable();
            $table->timestamp('post_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_posts');
    }
};
