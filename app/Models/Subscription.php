<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'renewed_at', 'expired_at'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions():HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
