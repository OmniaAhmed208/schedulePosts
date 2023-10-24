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
            $table->string('type');
            $table->string('page_id');
            $table->string('page_name');
            $table->string('page_link',1000);
            $table->string('page_img',1000)->nullable();
            $table->string('post_id');
            $table->string('post_img',1000)->nullable();
            $table->string('post_link',1000);
            $table->string('post_caption',1000)->nullable();
            $table->string('post_date');
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
