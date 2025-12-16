<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'movie_title',
        'studio',
        'seat',
        'show_time',
        'price',
        'user_name'
    ];
}
