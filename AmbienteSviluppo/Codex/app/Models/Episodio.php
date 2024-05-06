<?php

namespace App\Models;

use Database\Seeders\SerieTv;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Episodio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "Episodi";
    protected $primaryKey = "idEpisodio";

    protected $fillable = [
        'serieTv',
        'titolo',
        'durata',
        'stagione',
        'datiEp',
        'anno',
        'trama',
    ];




    public function serieTv()
    {
        return $this->belongsTo(SeriesTv::class,  'idSerieTv');
    }


    // Funzione per ottenere l'id della serie TV dato l'id dell'episodio
    public static function getIdSerieTvFromEpisodio($idEpisodio)
    {
        $episodio = self::find($idEpisodio);
        if ($episodio) {
            return $episodio->serieTv;
        }
        return null;
    }

    // Funzione per ottenere tutti gli episodi di una certa serie TV
    public static function getEpisodiFromSerieTv($idSerieTv)
    {
        return self::where('serieTv', $idSerieTv)->get();
    }
}
