<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'first_name',
        'last_name',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address_line1;
        if ($this->address_line2) {
            $address .= ', ' . $this->address_line2;
        }
        $address .= ', ' . $this->postal_code . ' ' . $this->city;
        if ($this->state) {
            $address .= ', ' . $this->state;
        }
        $address .= ', ' . $this->country;

        return $address;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            // Si c'est la première adresse, la définir par défaut
            if (!UserAddress::where('user_id', $address->user_id)->where('type', $address->type)->exists()) {
                $address->is_default = true;
            }

            // Si on définit une nouvelle adresse par défaut, retirer le flag des autres
            if ($address->is_default) {
                UserAddress::where('user_id', $address->user_id)
                    ->where('type', $address->type)
                    ->update(['is_default' => false]);
            }
        });

        static::updating(function ($address) {
            // Si on définit une nouvelle adresse par défaut, retirer le flag des autres
            if ($address->is_default && $address->isDirty('is_default')) {
                UserAddress::where('user_id', $address->user_id)
                    ->where('type', $address->type)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
