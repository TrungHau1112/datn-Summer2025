<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GhtkApiToken extends Model
{
    use HasFactory;

    protected $table = 'ghtk_api_tokens';

    protected $fillable = [
        'token_name',
        'api_token',
        'access_rights',
        'expires_at',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'access_rights' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive()
    {
        return $this->is_active && !$this->isExpired();
    }
}
