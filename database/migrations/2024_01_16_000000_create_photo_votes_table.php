<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('photo_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('vote_type', 20)->default('like'); // like, contest_vote, poll_vote
            $table->string('category', 50)->nullable(); // contest category, poll category
            $table->integer('score')->default(1); // 1-5 for rating votes
            $table->json('metadata')->nullable(); // additional vote data
            $table->timestamps();
            
            $table->unique(['photo_id', 'user_id', 'vote_type', 'category']);
            $table->index(['photo_id', 'vote_type']);
            $table->index(['user_id', 'vote_type']);
            $table->index(['category', 'vote_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_votes');
    }
};
