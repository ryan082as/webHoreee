<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'address',
        'whatsapp_number',
        'operational_hours',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
