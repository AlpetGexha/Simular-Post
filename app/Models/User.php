<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Overtrue\LaravelFollow\Followable as FollowableModel;
use Overtrue\LaravelFollow\Traits\Followable;
use Overtrue\LaravelFollow\Traits\Follower;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, Follower, Followable, InteractsWithMedia;

    const AVATAR_ULR = 'https://i.pravatar.cc/640/480';

    protected $fillable = [
        'name', 'email', 'password', 'verified',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function generateAvatar()
    {
        return $this
            ->addMediaFromUrl(config('image.avatar_url'))
            ->toMediaCollection('avatar');
    }

    public function followersPosts(): BelongsToMany
    {
        return $this
            ->belongsToMany(Post::class, FollowableModel::class, 'user_id', 'followable_id', 'id', 'user_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): hasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('avatar')
            ->fit(Manipulations::FIT_CROP, 32, 32)
            ->nonQueued();
    }

    /**
     * Query will give you a stronger "People You May Know" list that considers both the exclusion of already followed users and the count of shared reactions as indicators of potential connections.
     *
     */
    public function scopePeopleYouMayKnow($query)
    {
        $authId = auth()->user()->id;

        return $query
            ->select('users.id', 'users.name', 'users.email')
            ->leftJoin('followables', function ($join) use ($authId) {
                $join->on('users.id', '=', 'followables.followable_id')
                    ->where('followables.user_id', $authId);
            })
            ->whereNull('followables.id')
            ->where('users.id', '<>', $authId)
            ->leftJoin('posts', 'posts.user_id', '=', 'users.id')
            ->leftJoin('markable_reactions', function ($join) use ($authId) {
                $join
                    ->on('markable_reactions.markable_id', '=', 'posts.id')
                    ->where('markable_reactions.user_id', $authId)
                    ->where('markable_reactions.markable_type', '=', 'posts');
            })
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByRaw('COUNT(markable_reactions.id) DESC, users.created_at DESC');
    }

    public function isVerified()
    {
        return $this->verified;
    }
}
