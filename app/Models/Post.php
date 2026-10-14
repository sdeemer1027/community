<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $fillable = [
        'body',
        'audience',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mediaItems(): BelongsToMany
    {
        return $this->belongsToMany(
            MediaItem::class,
            'post_media'
        )->withTimestamps();
    }
}
