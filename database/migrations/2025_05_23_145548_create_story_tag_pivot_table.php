<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('story_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->cascadeOnDelete();
            $table->foreignId('story_tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['story_id', 'story_tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('story_tag');
    }
};
