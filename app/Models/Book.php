<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'id', 'title', 'authors', 'publisher', 'image', 'description'
    ];
    //
}
