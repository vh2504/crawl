<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductW extends Model
{
 
    use HasFactory;

    protected $fillables = ['name', 'price', 'short_description', 'image'];
    public $timestamps = false;
}
