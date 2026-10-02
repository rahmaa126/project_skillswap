<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'min_score',
        'is_active',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'min_score' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'category_id');
    }

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class, 'skill_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(UserSkillScore::class, 'skill_id');
    }

    public function battleQuestions(): HasMany
    {
        return $this->hasMany(BattleQuestion::class, 'skill_id');
    }

    public function embeddings(): HasMany
    {
        return $this->hasMany(SkillEmbedding::class, 'skill_id');
    }
}
