<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'date_of_birth',
        'phone_number',
        'avatar_url',
        'type',
        'medical_license_number',
        'ic_number',
        'medical_license_document',
        'expertise',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'type' => 'integer',
        'ic_number' => 'integer',
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'patient') {
            return $this->hasRole('patient');
        }

        if ($panel->getId() === 'admin') {
            return ! $this->hasRole('patient');
        }

        return true;
    }

    // public function canAccessPanel(Panel $panel): bool
    // {
    //     return str_ends_with($this->email, 'admin@mysihat.com') && $this->hasVerifiedEmail();
    // }

}
