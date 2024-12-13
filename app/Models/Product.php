<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_name', 'price', 'stock', 'company_id', 'comment', 'image'];

    // Companyとのリレーション
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // 販売とのリレーション
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
