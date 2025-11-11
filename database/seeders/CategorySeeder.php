<?php

namespace Database\Seeders;

use App\Domain\Categories\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Người Khmer', 'slug' => 'nguoi-khmer', 'order' => 1],
            ['name' => 'Người Hoa', 'slug' => 'nguoi-hoa', 'order' => 2],
            ['name' => 'Di sản văn hóa phi vật thể', 'slug' => 'bao-ton-di-san-van-hoa-phi-vat-the', 'order' => 3],
            ['name' => 'Di sản văn hóa vật thể', 'slug' => 'bao-ton-di-san-van-hoa-vat-the', 'order' => 4],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'order' => $category['order'],
                    'is_active' => true,
                ]
            );
        }
    }
}

