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
        Schema::create('publish__posts', function (Blueprint $table) {
            $table->id();
            $table->integer('creator_id');
            $table->String('type');
            $table->String('postData',1000);
            $table->String('pageName')->nullable();
            $table->String('image')->nullable();
            $table->String('link',1000)->nullable();
            $table->String('status')->nullable();
            $table->String('scheduledTime');
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
        Schema::dropIfExists('publish__posts');
    }
};
