<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get JWT identifier
     *
     * @return string
     */
    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    /**
     * Custom claims to add in JWT payload
     *
     * @return string[]
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function refreshToken(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(RefreshToken::class, 'user_id');
    }

    public function workInfo()
    {
        return $this->hasMany(WorkInfo::class, 'user_id');
    }

    public function isUser()
    {
        return $this->role == 'user';
    }

    public function hasRole($role)
    {
        return $this->role == $role;
    }

    /**
     * Hash password before inserting database
     *
     * @return Attribute
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value)
        );
    }

    public function closeOngoingWorkInfo()
    {
        return $this->workInfo()->today()->update(['status' => 'closed']);
    }
}
