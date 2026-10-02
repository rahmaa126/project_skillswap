<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catWeb = SkillCategory::where('name', 'Web Development')->first()?->id;
        $catMobile = SkillCategory::where('name', 'Mobile Development')->first()?->id;
        $catAI = SkillCategory::where('name', 'Data Science & AI')->first()?->id;
        $catDesign = SkillCategory::where('name', 'UI/UX Design')->first()?->id;
        $catLang = SkillCategory::where('name', 'Language & Communication')->first()?->id;

        $skills = [
            [
                'category_id' => $catWeb,
                'name' => 'Laravel Framework',
                'description' => 'Modern PHP web development ecosystem: Eloquent, Artisan, Auth, Queues, and RESTful APIs.',
                'min_score' => 75,
                'is_active' => true,
            ],
            [
                'category_id' => $catWeb,
                'name' => 'Vue.js',
                'description' => 'Reactive frontend development using Vue 3 Composition API, Vite, Pinia, and Vue Router.',
                'min_score' => 70,
                'is_active' => true,
            ],
            [
                'category_id' => $catWeb,
                'name' => 'React.js',
                'description' => 'Building modern single-page applications and UI components with React hooks and Next.js.',
                'min_score' => 70,
                'is_active' => true,
            ],
            [
                'category_id' => $catMobile,
                'name' => 'Flutter',
                'description' => 'Cross-platform mobile apps for Android & iOS with Dart, Bloc architecture, and clean UI.',
                'min_score' => 70,
                'is_active' => true,
            ],
            [
                'category_id' => $catAI,
                'name' => 'Python for Data Analysis',
                'description' => 'Data wrangling, exploratory data analysis, and visualization using Pandas, NumPy, and Seaborn.',
                'min_score' => 70,
                'is_active' => true,
            ],
            [
                'category_id' => $catAI,
                'name' => 'Prompt Engineering & LLM',
                'description' => 'Structured prompting, few-shot techniques, RAG concepts, and integrating OpenAI/Claude APIs.',
                'min_score' => 65,
                'is_active' => true,
            ],
            [
                'category_id' => $catDesign,
                'name' => 'Figma UI Design',
                'description' => 'User interface design, comprehensive design systems, interactive prototypes, and auto-layout.',
                'min_score' => 70,
                'is_active' => true,
            ],
            [
                'category_id' => $catLang,
                'name' => 'English for Tech Speaking',
                'description' => 'Professional spoken English for international sprint standups, technical interviews, and presentations.',
                'min_score' => 70,
                'is_active' => true,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(['name' => $skill['name']], $skill);
        }
    }
}
