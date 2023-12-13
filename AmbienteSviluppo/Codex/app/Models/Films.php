<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Films extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "Film";
    protected $primaryKey = "idFilm";

    protected $fillable = [
        "titolo",
        "durata",
        "regista",
        "categoria",
        "anno",
        "trama",
        "trailer",
        "src"
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria');
    }
}
