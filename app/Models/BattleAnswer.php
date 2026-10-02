<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BattleAnswer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'battle_id',
        'question_id',
        'user_id',
        'chosen_option',
        'is_correct',
        'points_earned',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'chosen_option' => 'integer',
            'is_correct' => 'boolean',
            'points_earned' => 'integer',
            'answered_at' => 'datetime',
        ];
    }

    public function battle(): BelongsTo
    {
        return $this->belongsTo(Battle::class, 'battle_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(BattleQuestion::class, 'question_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
