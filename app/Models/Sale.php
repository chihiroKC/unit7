<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['product_id', 'created_at', 'updated_at'];

    // 製品とのリレーション
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
