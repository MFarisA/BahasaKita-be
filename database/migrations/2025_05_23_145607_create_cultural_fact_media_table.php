<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('cultural_fact_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_fact_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['image', 'video']);
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('url');
            $table->string('mime_type');
            $table->integer('size');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index('cultural_fact_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cultural_fact_media');
    }
};
