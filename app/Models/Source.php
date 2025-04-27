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

    public static function validationRules($id = null)
    {
        return [
            'ip' => 'required|ip',
            'provider_ip' => 'nullable|string',
            'vmta' => 'nullable|string',
            'from' => 'required|string',
            'return_path' => 'required|string',
            'spf' => 'required|in:pass,fail,softfail,neutral,none,permerror,temperror',
            'dkim' => 'required|in:pass,fail,none,permerror,temperror,policy',
            'dmarc' => 'required|in:pass,fail,none,permerror,temperror,bestguesspass',
            'date' => 'required|date',
            'email' => 'required|string',
            'message_path' => 'required|in:inbox,spam',
            'colonne' => 'nullable|string',
            'redirect_link' => 'nullable|string',
            'header' => 'required|string',
            'body' => 'required|string',
        ];
    }
}
