<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'inscription_id',
        'type',
        'nom_fichier',
        'chemin_fichier',
        'mime_type',
        'taille',
        'genere_systeme',
        'genere_le',
    ];

    protected $casts = [
        'genere_systeme' => 'boolean',
        'genere_le'      => 'datetime',
    ];

    // ── Accesseurs ────────────────────────────────────────

    /** URL publique du fichier */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->chemin_fichier);
    }

    /** Taille lisible (ex: 1.2 Mo) */
    public function getTailleFormateeAttribute(): string
    {
        $taille = $this->taille ?? 0;
        if ($taille >= 1048576) return round($taille / 1048576, 1) . ' Mo';
        if ($taille >= 1024)    return round($taille / 1024, 1) . ' Ko';
        return $taille . ' o';
    }

    /** Libellé du type */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'acte_naissance'        => "Extrait d'acte de naissance",
            'bulletin'              => 'Bulletin scolaire',
            'certificat_medical'    => 'Certificat médical',
            'piece_identite_parent' => 'Pièce d\'identité parent',
            'photo_identite'        => 'Photo d\'identité',
            'fiche_inscription'     => 'Fiche d\'inscription (générée)',
            'carte_scolarite'       => 'Carte de scolarité (générée)',
            default                 => $this->type,
        };
    }

    // ── Relations ─────────────────────────────────────────

    /** Inscription à laquelle ce document appartient */
    public function inscription()
    {
        return $this->belongsTo(Inscription::class, 'inscription_id');
    }
}