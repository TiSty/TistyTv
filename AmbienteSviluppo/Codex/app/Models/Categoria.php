<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ="categorie";
    protected $primaryKey =  "idCategoria";

    protected $fillable=[
        "nome",
        "src"
    ];


    public function films(){
        return $this->hasMany(Films::class, 'idCategoria');
    }
    public function seriesTv(){
        return $this->hasMany(SeriesTv::class, 'idCategoria');
    }

}
