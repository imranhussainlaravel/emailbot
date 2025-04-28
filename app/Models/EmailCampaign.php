<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $table = 'email_campaigns';

    protected $fillable = [
        'title',
        'subject',
        'content',
        'sender_user_id',
        'smtp_from_email',
        'reply_to',
        'emails_sent',
        'emails_opened',
        'emails_bounced',
        'sent_at',
    ];

    protected $dates = [
        'sent_at',
        'created_at',
        'updated_at',
    ];
}
