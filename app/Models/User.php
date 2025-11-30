<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'points',
        'total_spent',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'total_spent' => 'decimal:2',
        ];
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function addPoints($points)
    {
        $this->increment('points', $points);
    }

    public function deductPoints($points)
    {
        if ($this->points >= $points) {
            $this->decrement('points', $points);
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function shippingAddresses(): HasMany
    {
        return $this->hasMany(UserAddress::class)->where('type', 'shipping');
    }

    public function billingAddresses(): HasMany
    {
        return $this->hasMany(UserAddress::class)->where('type', 'billing');
    }

    public function defaultShippingAddress()
    {
        return $this->hasMany(UserAddress::class)
            ->where('type', 'shipping')
            ->where('is_default', true)
            ->first();
    }

    public function defaultBillingAddress()
    {
        return $this->hasMany(UserAddress::class)
            ->where('type', 'billing')
            ->where('is_default', true)
            ->first();
    }
}
