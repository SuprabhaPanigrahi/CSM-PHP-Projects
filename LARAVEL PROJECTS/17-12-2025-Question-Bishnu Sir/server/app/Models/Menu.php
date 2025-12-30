<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'route',
        'icon',
        'order',
        'is_active',
        'customer_types'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    // Get menu items for a specific customer type
    public static function getForCustomerType($customerType)
    {
        return self::where('is_active', true)
            ->whereRaw("FIND_IN_SET(?, customer_types)", [$customerType])
            ->orderBy('order')
            ->get();
    }

    // Check if a route is accessible for customer type
    public static function canAccess($route, $customerType)
    {
        return self::where('route', $route)
            ->where('is_active', true)
            ->whereRaw("FIND_IN_SET(?, customer_types)", [$customerType])
            ->exists();
    }
}