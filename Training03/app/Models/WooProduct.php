<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WooProduct extends Model
{
    use HasFactory;
    protected $table = 'wooproducts';
    protected $fillables = ['ProductName', 'Description','ShortDescription', 'RegularPrice', 'SalePrice', 'Image', 'Style', 'Color', 'Size', 'Tags'];
    public $timestamps = false;
}
