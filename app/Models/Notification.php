<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'envoyee_par',
        'parent_id',
        'eleve_id',
        'sujet',
        'message',
        'type',
        'email_envoye',
        'email_envoye_le',
        'lu_le',
    ];

    protected $casts = [
        'email_envoye'    => 'boolean',
        'email_envoye_le' => 'datetime',
        'lu_le'           => 'datetime',
    ];

    // ── Accesseurs ────────────────────────────────────────

    public function getEstLueAttribute(): bool
    {
        return !is_null($this->lu_le);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'paiement' => 'Paiement',
            'urgente'  => 'Alerte urgente',
            default    => 'Information',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'paiement' => 'bi-wallet2',
            'urgente'  => 'bi-exclamation-triangle-fill',
            default    => 'bi-info-circle-fill',
        };
    }

    // ── Relations ─────────────────────────────────────────

    /** Admin qui a envoyé */
    public function envoyeur()
    {
        return $this->belongsTo(User::class, 'envoyee_par');
    }

    /** Parent destinataire */
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    /** Élève concerné (optionnel) */
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'eleve_id');
    }

    // ── Scopes ────────────────────────────────────────────

    public function scopeNonLues($query)
    {
        return $query->whereNull('lu_le');
    }

    public function scopePourParent($query, int $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    public function scopeDeType($query, string $type)
    {
        return $query->where('type', $type);
    }
}