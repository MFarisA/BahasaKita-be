<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('story_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['image', 'audio', 'video']);
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('url');
            $table->string('mime_type');
            $table->integer('size');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index(['story_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('story_media');
    }
};
