<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_system_role',
    ];

    protected function casts(): array
    {
        return [
            'is_system_role' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

public function permissions(): BelongsToMany
{
    return $this->belongsToMany(Permission::class);
}







}
