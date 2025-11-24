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
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan')->default('free')->after('email');
            $table->integer('daily_domain_views')->default(0)->after('plan');
            $table->dateTime('last_domain_view_reset_at')->nullable()->after('daily_domain_views');
            $table->boolean('is_admin')->default(false)->after('last_domain_view_reset_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan', 'daily_domain_views', 'last_domain_view_reset_at', 'is_admin']);
        });
    }
};
