<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ParentModel extends Model
{
    use HasFactory;

    // Nom de la table BDD
    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'civilite',
        'nom',
        'prenom',
        'lien_parente',
        'telephone',
        'telephone_secondaire',
        'adresse',
        'ville',
        'arrondissement',
        'code_postal',
    ];

    // ── Accesseur : nom complet ────────────────────────────
    public function getNomCompletAttribute(): string
    {
        return trim(($this->civilite ? $this->civilite . ' ' : '') . $this->prenom . ' ' . $this->nom);
    }

    // ── Relations ─────────────────────────────────────────

    /** Compte utilisateur lié */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Tous les élèves de ce parent */
    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'parent_id');
    }

    /** Toutes les notifications reçues */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'parent_id');
    }

    /** Toutes les échéances de paiement */
    public function echeances()
    {
        return $this->hasMany(Echeance::class, 'parent_id');
    }
}