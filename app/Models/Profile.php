<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'avatar_url',
        'bio',
        'city',
        'phone',
        'avg_rating',
        'total_swaps',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'avg_rating' => 'decimal:2',
            'total_swaps' => 'integer',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
