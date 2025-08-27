<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_content', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['trivia', 'joke']);
            $table->string('title');
            $table->text('content');
            $table->date('display_date');
            $table->boolean('is_active')->default(true);
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['type', 'display_date', 'is_active']);
            $table->unique(['type', 'display_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_content');
    }
};
