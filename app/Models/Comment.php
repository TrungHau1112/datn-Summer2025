<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScope;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, QueryScope;
    // use SoftDeletes;
    protected $fillable = [
        'user_id',
        'collection_id',
        'content',
        'parent_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mối quan hệ với bộ sưu tập
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    // Mối quan hệ cha (bình luận gốc)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    // Mối quan hệ con (các bình luận trả lời)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    // public function countReplies()
    // {
    //     return $this->replies()->count();
    // }
    public function countReplies()
    {
        $total = $this->replies()->count();

        foreach ($this->replies as $reply) {

            $total += $reply->countReplies();
        }

        return $total;
    }
}
