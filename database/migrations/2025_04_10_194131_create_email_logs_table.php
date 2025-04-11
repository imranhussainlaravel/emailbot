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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->int('compaign_id');
            $table->string('subject');
            $table->text('recipients'); // store as JSON (array of emails)
            $table->enum('status', ['sent', 'opened', 'bounced'])->default('sent');
            $table->text('bounce_reason')->nullable(); // Optional field for bounce details
            $table->timestamp('opened_at')->nullable(); // When the email was opened
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
