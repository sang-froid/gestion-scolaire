<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'nom',
        'niveau',
        'annee_scolaire',
        'capacite_max',
        'enseignant_responsable',
        'active',
    ];

    protected $casts = ['active' => 'boolean'];

    // ── Accesseurs ────────────────────────────────────────

    /** Nombre d'élèves actuellement affectés */
    public function getNbElevesAttribute(): int
    {
        return $this->inscriptions()->where('statut', 'validee')->count();
    }

    /** Nombre de places restantes */
    public function getPlacesRestantesAttribute(): int
    {
        return max(0, $this->capacite_max - $this->nb_eleves);
    }

    /** Vrai si la classe est pleine */
    public function estPleine(): bool
    {
        return $this->places_restantes <= 0;
    }

    /** Pourcentage de remplissage */
    public function getTauxRemplissageAttribute(): int
    {
        if ($this->capacite_max === 0) return 0;
        return (int) round(($this->nb_eleves / $this->capacite_max) * 100);
    }

    // ── Relations ─────────────────────────────────────────

    /** Toutes les inscriptions affectées à cette classe */
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'classe_id');
    }

    /** Les élèves de cette classe (via inscriptions validées) */
    public function eleves()
    {
        return $this->hasManyThrough(
            Eleve::class,
            Inscription::class,
            'classe_id',  // FK sur inscriptions
            'id',         // PK sur eleves
            'id',         // PK sur classes
            'eleve_id'    // FK sur inscriptions
        );
    }
}