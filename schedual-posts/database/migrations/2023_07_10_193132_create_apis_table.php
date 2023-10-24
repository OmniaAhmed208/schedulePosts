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
        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->integer('creator_id');
            $table->string('user_name');
            $table->string('email');
            $table->string('user_pic',1000)->nullable();
            $table->string('social_type');
            $table->string('user_account_id');
            $table->string('token',1000);
            $table->string('token_secret',1000)->nullable();
            $table->string('user_status')->nullable();
            $table->string('page_name')->nullable();
            $table->integer('update_interval')->default(60);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apis');
    }
};
