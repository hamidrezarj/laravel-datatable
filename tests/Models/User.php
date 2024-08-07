<?php

namespace HamidRrj\LaravelDatatable\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
