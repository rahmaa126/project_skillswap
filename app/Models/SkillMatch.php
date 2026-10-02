<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    public $timestamps = false;

    protected $fillable = [
        'user_a_id',
        'user_b_id',
        'skill_a_id',
        'skill_b_id',
        'match_score',
        'status',
        'match_source',
        'score_breakdown',
        'ai_reason',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'match_score' => 'decimal:2',
            'score_breakdown' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function userA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_a_id');
    }

    public function userB(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_b_id');
    }

    public function skillA(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_a_id');
    }

    public function skillB(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_b_id');
    }

    public function battles(): HasMany
    {
        return $this->hasMany(Battle::class, 'match_id');
    }

    public function swapSessions(): HasMany
    {
        return $this->hasMany(SwapSession::class, 'match_id');
    }
}
