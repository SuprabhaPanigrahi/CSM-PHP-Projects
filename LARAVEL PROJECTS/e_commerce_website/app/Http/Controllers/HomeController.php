<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = [
            [
                'name' => 'Premium Smart Watch',
                'price' => 199.99,
                'image' => 'build/assets/images/smart_watch.jpg',
                'description' => 'Track your fitness and stay connected with this advanced smartwatch.'
            ],
            [
                'name' => 'Wireless Headphones',
                'price' => 129.49,
                'image' => 'build/assets/images/headphone.jpg',
                'description' => 'Experience deep bass and crystal clear sound with premium wireless audio.'
            ],
            [
                'name' => 'Gaming Mouse',
                'price' => 49.99,
                'image' => 'build/assets/images/gaming_mouse.jpg',
                'description' => 'High precision gaming mouse with RGB lighting and ergonomic design.'
            ],
            [
                'name' => 'Bluetooth Speaker',
                'price' => 89.99,
                'image' => 'build/assets/images/bluetooth_speaker.jpg',
                'description' => 'Portable speaker with rich sound and long-lasting battery life.'
            ],
            [
                'name' => 'DSLR Camera',
                'price' => 899.00,
                'image' => 'build/assets/images/dslr.jpg',
                'description' => 'Capture stunning photos with this professional DSLR camera.'
            ],
            [
                'name' => '4K LED TV',
                'price' => 599.99,
                'image' => 'build/assets/images/led.jpg',
                'description' => 'Enjoy a cinematic experience with ultra HD clarity and vibrant colors.'
            ],
            [
                'name' => 'Laptop Pro 15"',
                'price' => 1299.00,
                'image' => 'build/assets/images/photo-1517336714731-489689fd1ca8.avif',
                'description' => 'Powerful high-performance laptop for work, gaming, and creativity.'
            ],
            [
                'name' => 'Noise Cancelling Earbuds',
                'price' => 74.99,
                'image' => 'build/assets/images/photo-1590650046871-2f1d1e39d5f4.avif',
                'description' => 'Experience immersive sound with active noise cancellation.'
            ],
            [
                'name' => 'Smartphone X Pro',
                'price' => 999.00,
                'image' => 'build/assets/images/photo-1511707171634-5f897ff02aa9.avif',
                'description' => 'A premium smartphone with unbeatable camera and performance.'
            ],
            [
                'name' => 'Gaming Keyboard',
                'price' => 69.99,
                'image' => 'build/assets/images/photo-1517336714731-489689fd1cab.avif',
                'description' => 'Mechanical RGB keyboard built for fast response and durability.'
            ],
        ];

        return view('products.home', [
            'title' => 'Home',
            'products' => $products
        ]);
    }
}
