<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_local');
            $table->text('content');
            $table->text('content_local');
            $table->string('language'); // javanese, sundanese, etc.
            $table->string('region'); // java, sumatra, etc.
            $table->enum('category', ['legend', 'folktale', 'myth', 'historical', 'moral']);
            $table->text('moral_lesson')->nullable();
            $table->json('cultural_elements')->nullable(); // array of cultural elements
            $table->text('setting')->nullable();
            $table->text('pronunciation_guide')->nullable();
            $table->string('slug')->unique();
            $table->integer('view_count')->default(0);
            $table->integer('popularity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['language', 'region']);
            $table->index(['category', 'is_active']);
            $table->index('popularity');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stories');
    }
};
