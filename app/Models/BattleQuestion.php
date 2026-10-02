<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BattleQuestion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'skill_id',
        'question',
        'options',
        'correct_option',
        'points',
        'difficulty',
        'explanation',
        'source',
        'status',
        'ai_model',
        'generation_log_id',
        'reviewed_by',
        'reviewed_at',
        'created_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'correct_option' => 'integer',
            'points' => 'integer',
            'reviewed_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function generationLog(): BelongsTo
    {
        return $this->belongsTo(AiLog::class, 'generation_log_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(BattleAnswer::class, 'question_id');
    }
}
