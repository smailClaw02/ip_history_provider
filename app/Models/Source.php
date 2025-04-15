<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $table = 'sources';

    protected $fillable = [
        'ip',
        'provider_ip',
        'vmta',
        'from',
        'return_path',
        'spf',
        'dkim',
        'dmark',
        'date',
        'email',
        'message_path',
        'colonne',
        'redirect_link',
        'header',
        'body',
        'domains'
    ];

    protected $casts = [
        'domains' => 'array',
    ];
}