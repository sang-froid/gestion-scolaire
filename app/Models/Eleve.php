<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Eleve extends Model
{
    use HasFactory;

    protected $table = 'eleves';

    protected $fillable = [
        'parent_id',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'nationalite',
        'numero_acte_naissance',
        'photo',
        'niveau_souhaite',
        'ancienne_ecole',
        'derniere_classe',
        'resultat_precedent',
        'groupe_sanguin',
        'infos_medicales',
        'matricule',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    // ── Accesseurs ────────────────────────────────────────

    /** Nom complet : DUPONT Jean */
    public function getNomCompletAttribute(): string
    {
        return strtoupper($this->nom) . ' ' . $this->prenom;
    }

    /** Age calculé */
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->date_naissance)->age;
    }

    /** URL de la photo (ou null) */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    // ── Relations ─────────────────────────────────────────

    /** Parent responsable */
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    /**
     * Inscription de l'année en cours.
     * Utiliser ->inscription pour avoir l'inscription active.
     */
    public function inscription()
    {
        return $this->hasOne(Inscription::class, 'eleve_id')
                    ->where('annee_scolaire', config('app.annee_scolaire', '2025-2026'))
                    ->latest();
    }

    /** Toutes les inscriptions (historique) */
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'eleve_id');
    }

    /** Documents liés (via inscription) */
    public function documents()
    {
        return $this->hasManyThrough(
            Document::class,
            Inscription::class,
            'eleve_id',      // FK sur inscriptions
            'inscription_id' // FK sur documents
        );
    }
}