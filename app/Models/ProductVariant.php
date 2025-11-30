<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'sku',
        'price',
        'compare_price',
        'stock_quantity',
        'is_active',
        'images'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active' => 'boolean',
        'images' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function getDisplayNameAttribute()
    {
        $parts = [];
        
        if ($this->size) {
            $parts[] = $this->size->name;
        }
        
        if ($this->color) {
            $parts[] = $this->color->name;
        }
        
        return empty($parts) ? 'Standard' : implode(' - ', $parts);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->getPrice()) {
            return round((($this->compare_price - $this->getPrice()) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getPrice()
    {
        return $this->price ?: $this->product->price;
    }
}
