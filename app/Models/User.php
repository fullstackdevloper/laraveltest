<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Scope to filter users with expired subscription before a given date.
     */

    public function scopeSubscriptionExpired($query, $today)
    {
        return $query->whereHas('latestSubsription', function ($query) use ($today) {
            $query->where('end_date', '<', $today);
        });
    }

    /**
     * Scope to filter users with given role.
     */

    public function scopeOfType(Builder $query, string $roleName)
    {
        return $query->whereHas('roles', function ($subquery) use ($roleName) {
            $subquery->where('name', $roleName);
        });
    }


    /**
     * Define the many-to-many relationship with roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Roles::class, 'role_user', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Define the one-to-one relationship with the active subscription.
     */
    public function latestSubsription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    /**
     * Define the one-to-many relationship with subscriptions.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Define the one-to-many relationship with IPN entries.
     */
    public function ipn(): HasMany
    {
        return $this->hasMany(Ipn::class);
    }

    /**
     * Get the plan associated with the user's active subscription.
     */
    public function plan(): HasOne
    {
        return $this->activeSubsription->plan;
    }

    /**
     * Remove user data by clearing the profile picture.
     */
    public function removeUserData()
    {
        $this->profile_picture = null;
        $this->save();
    }
}
