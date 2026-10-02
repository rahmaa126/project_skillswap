<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSkillScore extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'skill_id',
        'score',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
