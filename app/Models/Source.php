<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'provider_ip',
        'vmta',
        'from',
        'return_path',
        'spf',
        'dkim',
        'dmarc',
        'date',
        'email',
        'message_path',
        'colonne',
        'redirect_link',
        'header',
        'body'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}