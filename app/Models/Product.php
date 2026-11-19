<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'slug',
        'price',
        'thumbnail_url',
        'image_url',
        'description',
        'specs',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'specs' => 'array',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
