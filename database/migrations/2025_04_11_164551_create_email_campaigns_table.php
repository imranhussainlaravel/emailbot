<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // internal name for campaign
            $table->string('subject'); // actual email subject
            $table->text('content'); // email HTML/text content
            $table->foreignId('sender_user_id')->nullable()->constrained('users')->nullOnDelete(); // optional: Laravel user who started it
            $table->string('smtp_from_email'); // from which email SMTP this campaign was sent
            $table->string('reply_to')->nullable();
            $table->unsignedInteger('emails_sent')->default(0);
            $table->unsignedInteger('emails_opened')->default(0);
            $table->unsignedInteger('emails_bounced')->default(0);
            $table->timestamp('sent_at')->nullable(); // when the campaign was actually sent
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaigns');
    }
};
