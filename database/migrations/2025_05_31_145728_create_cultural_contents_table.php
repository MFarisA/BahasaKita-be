<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cultural_contents', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['story', 'proverb', 'trivia']);
            $table->string('language')->default('BahasaKita');
            
            // Fields for stories
            $table->string('title')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('image_url')->nullable();
            $table->longText('full_content')->nullable();
            
            // Fields for proverbs
            $table->text('text')->nullable();
            $table->text('translation')->nullable();
            $table->text('explanation')->nullable();
            
            // Fields for trivia
            $table->string('category')->nullable();
            $table->text('fact')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cultural_contents');
    }
};
