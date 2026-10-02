<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SwapSession extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'match_id',
        'requester_id',
        'partner_id',
        'requester_skill_id',
        'partner_skill_id',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(SkillMatch::class, 'match_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function requesterSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'requester_skill_id');
    }

    public function partnerSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'partner_skill_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'session_id');
    }

    public function videoCalls(): HasMany
    {
        return $this->hasMany(VideoCall::class, 'session_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'session_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'session_id');
    }
}
