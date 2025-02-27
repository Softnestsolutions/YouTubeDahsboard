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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable();
            $table->string('channel_id')->nullable();
            $table->string('title');
            $table->text('description'); // Changed to text, as descriptions can be long
            $table->string('thumbnail');
            $table->integer('subscriber_count'); // ✅ Fixed spelling
            $table->integer('video_count');
            $table->integer('view_count');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
