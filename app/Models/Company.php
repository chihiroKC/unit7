<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Company extends Model
{

    use HasFactory;
    
    protected $fillable = ['company_name', 'street_address', 'representative_name', 'created_at', 'updated_at'];

    // 製品とのリレーション
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
