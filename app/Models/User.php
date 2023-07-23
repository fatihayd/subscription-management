<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = ['name', 'email', 'password',];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function subscriptions():HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
