<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'actif'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['actif' => 'boolean'];

    // ── Helpers rôle ──────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    // ── Relations ─────────────────────────────────────────

    /**
     * Le profil parent lié à ce compte utilisateur.
     * Utilisé dans le sidebar et les controllers parent.
     * Appelé avec : auth()->user()->parentModel
     * (on évite "parent" qui est un mot réservé PHP)
     */
    public function parentModel()
    {
        return $this->hasOne(ParentModel::class, 'user_id');
    }
}
