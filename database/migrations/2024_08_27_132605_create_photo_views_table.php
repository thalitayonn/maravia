<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photo_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->timestamp('viewed_at');
            
            $table->index(['photo_id', 'viewed_at']);
            $table->index(['ip_address', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_views');
    }
};
