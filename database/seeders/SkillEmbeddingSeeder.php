<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillEmbedding;
use Illuminate\Database\Seeder;

class SkillEmbeddingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = Skill::all();
        $model = 'text-embedding-3-small';

        foreach ($skills as $skill) {
            $text = $skill->name.': '.($skill->description ?? '');
            $contentHash = hash('sha256', $text);

            // Generate deterministic mock embedding vector (length 8 for testing)
            $vector = [];
            for ($i = 0; $i < 8; $i++) {
                $vector[] = round(sin($skill->id + $i), 4);
            }

            SkillEmbedding::updateOrCreate(
                [
                    'skill_id' => $skill->id,
                    'model' => $model,
                ],
                [
                    'embedding' => $vector,
                    'content_hash' => $contentHash,
                    'updated_at' => now(),
                ]
            );
        }
    }
}
