<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->string('activityable_type')->nullable()->change();
            $table->unsignedBigInteger('activityable_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->string('activityable_type')->nullable(false)->change();
            $table->unsignedBigInteger('activityable_id')->nullable(false)->change();
        });
    }
};
