<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $uploads = '/storage/photos/';

    public function products()
    {
        return $this->belongsToMany(Product::class);

    }

    public function getPathAttribute($photo)
    {
        return $this->uploads . $photo;
    }
}
