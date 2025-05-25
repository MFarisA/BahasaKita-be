<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('story_characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('name_local')->nullable();
            $table->text('description')->nullable();
            $table->enum('role', ['protagonist', 'antagonist', 'supporting', 'narrator']);
            $table->enum('type', ['human', 'animal', 'spirit', 'deity', 'mythical']);
            $table->timestamps();
            
            $table->index('story_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('story_characters');
    }
};
