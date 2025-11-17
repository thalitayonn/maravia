<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('slug')->unique()->after('title');
            $table->string('excerpt', 500)->nullable()->after('slug');
            $table->longText('content')->nullable()->after('excerpt');
            $table->string('cover_image')->nullable()->after('content');
            $table->boolean('is_published')->default(false)->after('cover_image');
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->foreignId('author_id')->nullable()->after('published_at')->constrained('users')->nullOnDelete();
            $table->index(['is_published','published_at']);
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex(['is_published','published_at']);
            $table->dropConstrainedForeignId('author_id');
            $table->dropColumn([
                'title','slug','excerpt','content','cover_image','is_published','published_at'
            ]);
        });
    }
};