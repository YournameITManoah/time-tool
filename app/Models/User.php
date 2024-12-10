<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'available_hours',
        'planned_hours',
        'role_id',
        'password',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'available_hours' => 'integer',
        'planned_hours' => 'integer',
        'role_id' => 'integer',
    ];

    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class);
    }

    public function userTasks(): HasMany
    {
        return $this->hasMany(UserTask::class);
    }

    public function role(): BelongsTo
    {
        return $this->BelongsTo(Role::class);
    }

    public function isAdmin(): bool
    {
        return $this->role->id === \App\Role::ADMIN->value;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('view-admin', User::class);
    }
}
