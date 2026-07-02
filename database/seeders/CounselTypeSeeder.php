<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\CounselType;

class CounselTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Children Counseling',    'icon' => 'fas fa-child',              'color' => '#1565c0', 'description' => 'Counseling services for children and adolescents.', 'sort_order' => 1],
            ['name' => 'Pre-Marital Counseling',  'icon' => 'fas fa-heart',              'color' => '#880e4f', 'description' => 'Guidance for couples preparing for marriage.', 'sort_order' => 2],
            ['name' => 'Study Counseling',        'icon' => 'fas fa-graduation-cap',     'color' => '#1b5e20', 'description' => 'Academic guidance and study-related support.', 'sort_order' => 3],
            ['name' => 'Work Counseling',         'icon' => 'fas fa-briefcase',          'color' => '#e65100', 'description' => 'Career and workplace-related counseling.', 'sort_order' => 4],
            ['name' => 'Family Counseling',       'icon' => 'fas fa-users',              'color' => '#4a148c', 'description' => 'Support for family relationships and dynamics.', 'sort_order' => 5],
            ['name' => 'Mental Health',           'icon' => 'fas fa-brain',              'color' => '#006064', 'description' => 'General mental health and psychological support.', 'sort_order' => 6],
            ['name' => 'Grief Counseling',        'icon' => 'fas fa-hand-holding-heart', 'color' => '#37474f', 'description' => 'Support for loss and bereavement.', 'sort_order' => 7],
            ['name' => 'Relationship Counseling', 'icon' => 'fas fa-user-friends',       'color' => '#b71c1c', 'description' => 'Improving interpersonal relationships.', 'sort_order' => 8],
            ['name' => 'Substance Counseling',    'icon' => 'fas fa-pills',              'color' => '#4e342e', 'description' => 'Support for substance use and addiction.', 'sort_order' => 9],
            ['name' => 'Financial Counseling',    'icon' => 'fas fa-dollar-sign',        'color' => '#33691e', 'description' => 'Guidance on financial stress and planning.', 'sort_order' => 10],
        ];

        foreach ($types as $type) {
            CounselType::updateOrCreate(
                ['slug' => Str::slug($type['name'])],
                array_merge($type, ['slug' => Str::slug($type['name']), 'status' => 'active'])
            );
        }

        $this->command->info('Counsel types seeded: ' . count($types) . ' records.');
    }
}
