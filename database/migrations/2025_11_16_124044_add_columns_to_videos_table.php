<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->text('description')->nullable()->after('title');
            $table->foreignId('category_id')->nullable()->after('description')->constrained()->nullOnDelete();
            $table->string('file_path')->after('category_id');
            $table->string('poster_path')->nullable()->after('file_path');
            $table->float('duration')->nullable()->after('poster_path');
            $table->boolean('is_active')->default(true)->after('duration');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->foreignId('uploaded_by')->nullable()->after('is_featured')->constrained('users')->nullOnDelete();
            $table->index(['is_active','is_featured']);
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropIndex(['is_active','is_featured']);
            $table->dropConstrainedForeignId('uploaded_by');
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn([
                'title','description','file_path','poster_path','duration','is_active','is_featured'
            ]);
        });
    }
};