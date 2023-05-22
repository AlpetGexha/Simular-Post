<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Maize\Markable\Markable;

class PostComments extends Model
{
    use HasFactory, Markable;

    protected static array $marks = [
        Reaction::class,
    ];

    protected $fillable = [
        'post_id',
        'user_id',
        'comment_text',
        'parent_comment_id',
        'parent_comment_id',
    ];

    protected static function booted()
    {
        static::created(function (PostComments $comment) {
            Post::where('id', $comment->post_id)->increment('comments_count');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_comment_id', 'id');
    }

    public function popularReply()
    {
        return $this
            ->hasOne(self::class, 'parent_comment_id', 'id')
            ->with('user.media')
            ->withCount('reactions')
            ->orderByDesc('reactions_count');
    }
}
