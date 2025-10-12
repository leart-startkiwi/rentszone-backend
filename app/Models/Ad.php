<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
    use HasFactory;

    protected $table = 'ads';

    protected $fillable = [
        'image_url',
        'link_url',
        'title',
        'is_active',
        'price_per_month',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_per_month' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to get only active ads
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by price for weighted randomization
     */
    public function scopeOrderByWeight($query)
    {
        return $query->orderByRaw('RAND() * price_per_month DESC');
    }
}