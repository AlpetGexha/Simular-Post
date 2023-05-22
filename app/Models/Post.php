<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Maize\Markable\Markable;
use Maize\Markable\Models\Reaction;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Post extends Model implements HasMedia
{
    use HasFactory, Markable, InteractsWithMedia, HasRelationships, HasTags;

    // protected $fillable = [
    //     'user_id',
    //     'post_text',
    //     'link_url',
    //     'link_text',
    //     'post_id',
    //     'post_reactions_count',
    //     'comments_count',
    //     'shared_post_count',
    //     'media_count',
    //     'post_comments_reactions_count',
    //     ];
    protected static array $marks = [
        Reaction::class,
    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function (Post $post) {
            if (!is_null($post->post_id)) {
                Post::where('id', $post->post_id)->increment('shared_post_count');
            }
        });
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): hasMany
    {
        return $this->hasMany(PostComments::class);
    }

    public function sharedPost(): belongsTo
    {
        return $this
            ->belongsTo(self::class, 'id', 'post_id')
            ->whereNotNull('post_id');
    }

    public function popularComment(): hasOne
    {
        return $this
            ->hasOne(PostComments::class)
            ->whereNull('parent_comment_id')
            ->with('user.media')
            ->withCount('reactions')
            ->orderByDesc('reactions_count');
    }

    public function report()
    {
    }

    public function scopeOrderByPopularity($query)
    {
        return $query
        ->leftJoin('posts as p', 'p.id', '=', 'followables.followable_id')
        ->leftJoin('users as u', 'u.id', '=', 'p.user_id')
        ->orderByDesc(
            DB::raw(
                'posts.post_reactions_count +
                posts.post_comments_reactions_count +
                (posts.shared_post_count * 3) +
                (posts.comments_count * 2) +
                (CASE WHEN posts.media_count > 0 THEN 10 ELSE 0 END) -
                posts.reports_count +
                (CASE WHEN DATE(p.created_at) = CURDATE() THEN 60 ELSE 0 END) +
                (posts.tags_count * 2)'
            )
        );
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('posts')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
