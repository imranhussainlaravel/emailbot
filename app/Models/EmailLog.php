<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $table = 'email_logs'; // optional if following naming conventions

    protected $fillable = [
        'compaign_id',
        'recipients',
        'subject',
        'tracking_id',
        'opened_at',
        'status',
        'bounce_reason',
    ];
}
