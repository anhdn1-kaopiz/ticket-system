<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technical Support'
            ],
            [
                'name' => 'Billing'
            ],
            [
                'name' => 'Account Management'
            ],
            [
                'name' => 'Feature Request'
            ],
            [
                'name' => 'Bug Report'
            ],
            [
                'name' => 'General Inquiry'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name']
            ]);
        }
    }
}
