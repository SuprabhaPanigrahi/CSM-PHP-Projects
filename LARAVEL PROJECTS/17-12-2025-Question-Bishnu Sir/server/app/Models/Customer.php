<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_name',
        'address',
        'phone_number',
        'email',
        'password',
        'customer_type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'customer_id' => $this->customer_id,
            'customer_type' => $this->customer_type,
            'email' => $this->email,
            'name' => $this->customer_name
        ];
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'customer_id', 'customer_id');
    }
}