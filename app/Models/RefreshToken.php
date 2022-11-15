<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    use HasFactory;

    protected $table = 'refresh_token';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope token
     *
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function scopeToken(Builder $builder, $value): Builder
    {
        return $builder->where('token', $value);
    }
}
