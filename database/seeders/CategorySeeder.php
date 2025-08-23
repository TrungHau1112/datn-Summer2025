<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Smart Phone' => [
                'thumbnail' => '/client_asset_v1/imgs/page/homepage1/smartphone.png',
                'children' => [
                    'Phone Accessories',
                    'Phone Cases',
                    'Postpaid Phones',
                    'Refurbished Phones'
                ]
            ],
            'Television' => [
                'thumbnail' => '/client_asset_v1/imgs/page/homepage1/television.png',
                'children' => [
                    'HD DVD Players',
                    'Projection Screens',
                    'Television Accessories',
                    'TV-DVD Combos'
                ]
            ],
            'Computers' => [
                'thumbnail' => '/client_asset_v1/imgs/page/homepage1/computer.png',
                'children' => [
                    'Computer Components',
                    'Computer Accessories',
                    'Desktops',
                    'Monitors'
                ]
            ],
            'Electronics' => [
                'thumbnail' => '/client_asset_v1/imgs/page/homepage1/electric.png',
                'children' => [
                    'Office Electronics',
                    'Portable Audio & Video',
                    'Washing Machine',
                    'Accessories & Supplies'
                ]
            ],
            'Laptop & Tablet' => [
                'thumbnail' => '/client_asset_v1/imgs/page/homepage1/laptop.png',
                'children' => [
                    'Office laptop',
                    'Gaming laptop',
                    'Laptop accessories',
                    'Tablet'
                ]
            ],
            'Smartwatches' => [
                'thumbnail' => '/client_asset_v1/imgs/page/homepage1/smartwatches.png',
                'children' => [
                    'Sport Watches',
                    'Chronograph Watches',
                    'Kids Watches',
                    'Luxury Watches'
                ]
            ],
            'Gaming' => [
                'thumbnail' => '/client_asset_v1/imgs/page/homepage1/gaming.png',
                'children' => [
                    'Game Controllers',
                    'Gaming Keyboards',
                    'PC Gaming Mice',
                    'PC Game Headsets',
                    'Ecom'
                ]
            ],
            'Outdoor Camera' => [
                'thumbnail' => '/client_asset_v1/imgs/page/homepage1/outdoor.png',
                'children' => [
                    'Security & Surveillance',
                    'Surveillance DVR Kits',
                    'Surveillance NVR Kits',
                    'Smart Outdoor Lighting'
                ]
            ],
        ];

        foreach ($categories as $parent => $data) {
            $parentId = DB::table('categories')->insertGetId([
                'name' => $parent,
                'slug' => Str::slug($parent),
                'thumbnail' => $data['thumbnail'],  // Using unique thumbnail for each parent
                'parent_id' => null,
                'is_room' => 2,
                'publish' => 1,
                'meta_title' => $parent,
                'meta_description' => $parent . ' description',
                'meta_keyword' => $parent,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($data['children'] as $child) {
                DB::table('categories')->insert([
                    'name' => $child,
                    'slug' => Str::slug($child),
                    'thumbnail' => 'categories/default-subcategory.jpg',  // Default image for subcategories
                    'parent_id' => $parentId,
                    'is_room' => 2,
                    'publish' => 1,
                    'meta_title' => $child,
                    'meta_description' => $child . ' description',
                    'meta_keyword' => $child,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Brands with unique thumbnails
        $brands = [
            'Apple' => 'brands/apple.jpg',
            'Samsung' => 'brands/samsung.jpg',
            'Sony' => 'brands/sony.jpg',
            'Xiaomi' => 'brands/xiaomi.jpg',
            'Huawei' => 'brands/huawei.jpg',
            'Realme' => 'brands/realme.jpg',
            'Nokia' => 'brands/nokia.jpg'
        ];

        foreach ($brands as $brand => $thumbnail) {
            DB::table('categories')->insert([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'thumbnail' => $thumbnail,  // Using unique thumbnail for each brand
                'parent_id' => null,
                'is_room' => 1,
                'publish' => 1,
                'meta_title' => $brand,
                'meta_description' => "$brand official brand category",
                'meta_keyword' => $brand,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
