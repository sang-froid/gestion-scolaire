<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $table = 'inscriptions';

    protected $fillable = [
        'eleve_id',
        'classe_id',
        'numero_dossier',
        'annee_scolaire',
        'type',
        'statut',
        'motif_refus',
        'validee_le',
        'validee_par',
    ];

    protected $casts = [
        'validee_le' => 'datetime',
    ];

    // ── Boot : génération auto du numéro de dossier ───────
    protected static function booted(): void
    {
        static::creating(function (Inscription $inscription) {
            if (empty($inscription->numero_dossier)) {
                $inscription->numero_dossier = self::genererNumeroDossier();
            }
        });
    }

    public static function genererNumeroDossier(): string
    {
        $annee   = date('Y');
        $dernier = self::whereYear('created_at', $annee)->count() + 1;
        return 'INS-' . $annee . '-' . str_pad($dernier, 4, '0', STR_PAD_LEFT);
    }

    // ── Accesseurs ────────────────────────────────────────

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'validee'    => 'Validée',
            'refusee'    => 'Refusée',
            default      => 'En attente',
        };
    }

    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'validee' => 'validee',
            'refusee' => 'refusee',
            default   => 'attente',
        };
    }

    // ── Relations ─────────────────────────────────────────

    /** L'élève concerné */
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'eleve_id');
    }

    /** La classe affectée (nullable avant affectation) */
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /** L'admin qui a validé */
    public function validePar()
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    /** Documents joints à ce dossier */
    public function documents()
    {
        return $this->hasMany(Document::class, 'inscription_id');
    }

    /** Échéances de paiement liées */
    public function echeances()
    {
        return $this->hasMany(Echeance::class, 'inscription_id');
    }

    // ── Scopes ────────────────────────────────────────────

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValidees($query)
    {
        return $query->where('statut', 'validee');
    }

    public function scopeRefusees($query)
    {
        return $query->where('statut', 'refusee');
    }

    public function scopeAnnee($query, string $annee)
    {
        return $query->where('annee_scolaire', $annee);
    }
}