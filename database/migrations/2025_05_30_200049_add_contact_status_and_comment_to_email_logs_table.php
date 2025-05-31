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
        Schema::table('email_logs', function (Blueprint $table) {
            $table->enum('contact_status', ['none', 'contacted', 'not_interested', 'interested', 'contact_later', 'bounce'])
                  ->default('none')
                  ->after('bounce_reason'); // adjust position as needed
            $table->text('comment')->nullable()->after('contact_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn(['contact_status', 'comment']);
        });
    }
};
