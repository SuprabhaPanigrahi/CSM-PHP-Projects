<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'order' => 1,
                'customer_types' => 'silver,gold,diamond'
            ],
            [
                'name' => 'View Products',
                'route' => 'products',
                'icon' => 'fas fa-shopping-bag',
                'order' => 2,
                'customer_types' => 'silver,gold,diamond'
            ],
            [
                'name' => 'View Offers',
                'route' => 'offers',
                'icon' => 'fas fa-percent',
                'order' => 3,
                'customer_types' => 'diamond'
            ],
            [
                'name' => 'Make Purchase',
                'route' => 'purchase',
                'icon' => 'fas fa-cart-plus',
                'order' => 4,
                'customer_types' => 'gold,diamond'
            ],
            [
                'name' => 'Purchase History',
                'route' => 'purchase.history',
                'icon' => 'fas fa-history',
                'order' => 5,
                'customer_types' => 'gold,diamond'
            ]
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}