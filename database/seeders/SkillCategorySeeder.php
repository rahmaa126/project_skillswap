<?php

namespace Database\Seeders;

use App\Models\SkillCategory;
use Illuminate\Database\Seeder;

class SkillCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development'],
            ['name' => 'Mobile Development'],
            ['name' => 'Data Science & AI'],
            ['name' => 'UI/UX Design'],
            ['name' => 'Digital Marketing'],
            ['name' => 'Language & Communication'],
        ];

        foreach ($categories as $category) {
            SkillCategory::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
