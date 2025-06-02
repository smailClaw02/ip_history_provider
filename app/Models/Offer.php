<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_offer',
        'category',
        'name',
        'rev',
        'img',
        'link_img',
        'link_uns',
        'from',
        'sub',
        'count_lead'
    ];

    protected $casts = [
        'rev' => 'decimal:2',
    ];
}