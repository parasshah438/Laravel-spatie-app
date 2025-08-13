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
            $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->text('last_login_user_agent')->nullable()->after('last_login_ip');
            $table->timestamp('current_login_at')->nullable()->after('last_login_user_agent');
            $table->string('current_login_ip')->nullable()->after('current_login_at');
            $table->boolean('is_online')->default(false)->after('current_login_ip');
            $table->timestamp('last_activity_at')->nullable()->after('is_online');
            $table->integer('login_count')->default(0)->after('last_activity_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_login_at',
                'last_login_ip',
                'last_login_user_agent',
                'current_login_at',
                'current_login_ip',
                'is_online',
                'last_activity_at',
                'login_count'
            ]);
        });
    }
};
