<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfiguration extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the default plural form of the model name
    protected $table = 'email_configurations';

    // Specify which attributes can be mass assigned
    protected $fillable = [
        'email_name',
        'email_host',
        'email_port',
        'email_username',
        'email_password',
        'is_active',
    ];

    // Hide sensitive data like email password
    protected $hidden = [
        'email_password',
    ];
}
