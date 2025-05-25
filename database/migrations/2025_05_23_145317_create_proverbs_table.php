<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('proverbs', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->text('text_local');
            $table->text('meaning');
            $table->text('context')->nullable();
            $table->text('usage_example')->nullable();
            $table->string('language');
            $table->string('region');
            $table->text('pronunciation_guide')->nullable();
            $table->integer('popularity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index(['language', 'region']);
            $table->index('popularity');
        });
    }

    public function down()
    {
        Schema::dropIfExists('proverbs');
    }
};
