<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('publisher_otp_code', 6)->nullable()->after('nama_publisher');
            $table->timestamp('publisher_otp_expires_at')->nullable()->after('publisher_otp_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['publisher_otp_code', 'publisher_otp_expires_at']);
        });
    }
};
