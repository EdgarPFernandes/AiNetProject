<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'image',
        'custom',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
