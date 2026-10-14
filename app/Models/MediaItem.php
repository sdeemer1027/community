<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MediaItem extends Model
{
    protected $fillable = [
        'file_path',
        'media_type',
        'original_filename',
        'caption',
        'file_size',
        'mime_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'post_media'
        )->withTimestamps();
    }
}
