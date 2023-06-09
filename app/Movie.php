<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', 'description', 'rating', 'image'];

    protected $guarded = [
        'updated_at',
    ];
}
