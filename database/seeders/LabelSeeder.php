<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    public function run(): void
    {
        $labels = [
            [
                'name' => 'High Priority'
            ],
            [
                'name' => 'Medium Priority'
            ],
            [
                'name' => 'Low Priority'
            ],
            [
                'name' => 'In Progress'
            ],
            [
                'name' => 'Resolved'
            ],
            [
                'name' => 'Needs More Info'
            ],
            [
                'name' => 'Bug'
            ],
            [
                'name' => 'Feature'
            ],
            [
                'name' => 'Documentation'
            ],
            [
                'name' => 'Security'
            ]
        ];

        foreach ($labels as $label) {
            Label::create($label);
        }
    }
}
