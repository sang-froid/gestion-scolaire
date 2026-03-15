<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Echeance extends Model
{
    use HasFactory;

    protected $table = 'echeances';

    protected $fillable = [
        'inscription_id',
        'parent_id',
        'eleve_id',
        'libelle',
        'montant',
        'date_limite',
        'statut',
        'payee_le',
    ];

    protected $casts = [
        'date_limite' => 'date',
        'payee_le'    => 'datetime',
        'montant'     => 'decimal:2',
    ];

    // ── Accesseurs ────────────────────────────────────────

    /** Vrai si la date limite est dépassée et non payée */
    public function getEstEnRetardAttribute(): bool
    {
        return $this->statut === 'impayee' && $this->date_limite->isPast();
    }

    /** Montant formaté en FCFA */
    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    // ── Relations ─────────────────────────────────────────

    public function inscription()
    {
        return $this->belongsTo(Inscription::class, 'inscription_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'eleve_id');
    }

    // ── Scopes ────────────────────────────────────────────

    public function scopeImpayees($query)
    {
        return $query->where('statut', 'impayee');
    }

    public function scopeAVenir($query)
    {
        return $query->where('date_limite', '>=', now());
    }

    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'impayee')
                     ->where('date_limite', '<', now());
    }
}