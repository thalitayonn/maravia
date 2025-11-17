<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // User Favorites
        Schema::create('user_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('photo_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'photo_id']);
        });

        // User Collections
        Schema::create('user_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('cover_photo')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        // Collection Photos
        Schema::create('collection_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('user_collections')->onDelete('cascade');
            $table->foreignId('photo_id')->constrained()->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->unique(['collection_id', 'photo_id']);
        });

        // Photo Ratings
        Schema::create('photo_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('photo_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned(); // 1-5 stars
            $table->text('review')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'photo_id']);
        });

        // User Achievements
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('achievement_type'); // explorer, collector, reviewer, etc.
            $table->string('achievement_name');
            $table->text('description');
            $table->string('badge_icon');
            $table->string('badge_color');
            $table->integer('points')->default(0);
            $table->timestamp('earned_at');
            $table->timestamps();
        });

        // User Activity Log
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // view, favorite, download, rate, etc.
            $table->morphs('activityable'); // photo, collection, etc.
            $table->json('metadata')->nullable(); // additional data
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });

        // User Preferences
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('favorite_categories')->nullable();
            $table->json('favorite_tags')->nullable();
            $table->string('theme_preference')->default('light');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('new_photo_alerts')->default(false);
            $table->timestamps();
        });

        // User Stats (computed/cached values)
        Schema::create('user_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_views')->default(0);
            $table->integer('total_downloads')->default(0);
            $table->integer('total_favorites')->default(0);
            $table->integer('total_collections')->default(0);
            $table->integer('total_ratings')->default(0);
            $table->decimal('average_rating_given', 3, 2)->default(0);
            $table->integer('achievement_points')->default(0);
            $table->integer('user_level')->default(1);
            $table->integer('days_active')->default(0);
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
        });

        // Add additional fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('location')->nullable()->after('bio');
            $table->date('birth_date')->nullable()->after('location');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
            $table->boolean('is_active')->default(true)->after('gender');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'bio', 'location', 'birth_date', 'gender', 'is_active', 'last_login_at']);
        });
        
        Schema::dropIfExists('user_stats');
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('user_activities');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('photo_ratings');
        Schema::dropIfExists('collection_photos');
        Schema::dropIfExists('user_collections');
        Schema::dropIfExists('user_favorites');
    }
};
