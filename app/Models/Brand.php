<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(BrandProduct::class, 'brand_id', 'id');
    }
}
