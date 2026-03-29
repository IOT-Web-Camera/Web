<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // Les champs autorisés pour create()
    protected $fillable = ['name', 'email', 'password'];

    // Masquer le mot de passe et le token de session
    protected $hidden = ['password', 'remember_token'];

    // Cast pour la date de vérification email
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relation : un user a plusieurs caméras
    public function cameras()
    {
        return $this->hasMany(Camera::class, 'owner_id');
    }
}
