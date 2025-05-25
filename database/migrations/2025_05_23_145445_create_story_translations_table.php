<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('story_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->cascadeOnDelete();
            $table->string('language_code', 10); // en, id, etc.
            $table->string('title');
            $table->text('content');
            $table->text('moral_lesson')->nullable();
            $table->timestamps();
            
            $table->unique(['story_id', 'language_code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('story_translations');
    }
};
