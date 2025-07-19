<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'website',
        'logo',
    ];

    protected $casts = [
        'website' => 'string',
        'logo' => 'string',
    ];
}
