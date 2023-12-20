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
        'episodio',
        'anno',
        'trama',
    ];




    public function serieTv()
    {
        return $this->belongsTo(SeriesTv::class,  'idSerieTv');
    }
}
