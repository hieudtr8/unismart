<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'content', 'status', 'price', 'desc', 'thumbnail', 'cat_id', 'created_at'
    ];
}
