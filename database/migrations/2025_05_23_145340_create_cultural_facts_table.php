<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cultural_facts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['tradition', 'food', 'art', 'clothing', 'architecture', 'music', 'dance', 'ceremony']);
            $table->string('region');
            $table->string('language_context')->nullable();
            $table->json('related_stories')->nullable(); // array of story IDs
            $table->integer('popularity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['category', 'region']);
            $table->index('popularity');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cultural_facts');
    }
};
