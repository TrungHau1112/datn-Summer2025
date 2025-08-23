<?php

namespace App\Models;

use App\Traits\QueryScope;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use QueryScope;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'publish',
        'published_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
