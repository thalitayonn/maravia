<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // VIDEOS TABLE
        if (Schema::hasTable('videos')) {
            Schema::table('videos', function (Blueprint $table) {
                if (!Schema::hasColumn('videos', 'title')) {
                    $table->string('title')->after('id');
                }
                if (!Schema::hasColumn('videos', 'description')) {
                    $table->text('description')->nullable()->after('title');
                }
                if (!Schema::hasColumn('videos', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('description');
                }
                if (!Schema::hasColumn('videos', 'file_path')) {
                    $table->string('file_path')->after('category_id');
                }
                if (!Schema::hasColumn('videos', 'poster_path')) {
                    $table->string('poster_path')->nullable()->after('file_path');
                }
                if (!Schema::hasColumn('videos', 'duration')) {
                    $table->float('duration')->nullable()->after('poster_path');
                }
                if (!Schema::hasColumn('videos', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('duration');
                }
                if (!Schema::hasColumn('videos', 'is_featured')) {
                    $table->boolean('is_featured')->default(false)->after('is_active');
                }
                if (!Schema::hasColumn('videos', 'uploaded_by')) {
                    $table->unsignedBigInteger('uploaded_by')->nullable()->after('is_featured');
                }
            });
        }

        // ARTICLES TABLE
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                if (!Schema::hasColumn('articles', 'title')) {
                    $table->string('title')->after('id');
                }
                if (!Schema::hasColumn('articles', 'slug')) {
                    $table->string('slug')->after('title');
                }
                if (!Schema::hasColumn('articles', 'excerpt')) {
                    $table->string('excerpt', 500)->nullable()->after('slug');
                }
                if (!Schema::hasColumn('articles', 'content')) {
                    $table->longText('content')->nullable()->after('excerpt');
                }
                if (!Schema::hasColumn('articles', 'cover_image')) {
                    $table->string('cover_image')->nullable()->after('content');
                }
                if (!Schema::hasColumn('articles', 'is_published')) {
                    $table->boolean('is_published')->default(false)->after('cover_image');
                }
                if (!Schema::hasColumn('articles', 'published_at')) {
                    $table->timestamp('published_at')->nullable()->after('is_published');
                }
                if (!Schema::hasColumn('articles', 'author_id')) {
                    $table->unsignedBigInteger('author_id')->nullable()->after('published_at');
                }
            });
        }

        // Add unique constraint to articles slug if column exists
        try {
            if (Schema::hasColumn('articles', 'slug')) {
                Schema::table('articles', function (Blueprint $table) {
                    $table->unique('slug');
                });
            }
        } catch (Exception $e) {
            // Ignore if unique constraint already exists
        }
    }

    public function down(): void
    {
        // VIDEOS TABLE - Remove columns if they exist
        if (Schema::hasTable('videos')) {
            Schema::table('videos', function (Blueprint $table) {
                $columns = ['uploaded_by', 'is_featured', 'is_active', 'duration', 'poster_path', 'file_path', 'category_id', 'description', 'title'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('videos', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        // ARTICLES TABLE - Remove columns if they exist
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                // Drop unique constraint first if it exists
                try {
                    $table->dropUnique(['slug']);
                } catch (Exception $e) {
                    // Ignore if constraint doesn't exist
                }
                
                $columns = ['author_id', 'published_at', 'is_published', 'cover_image', 'content', 'excerpt', 'slug', 'title'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('articles', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};