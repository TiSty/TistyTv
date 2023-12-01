<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->getCampi();
    }



    //------------- PROTECTED ------------------------------------------------


    protected function getCampi()
    {
        // QUESTA FUNZIONE è PER LA FUNZIONE INDEX DOVE VISUALIZZO TUTTI I CAMPI
        return [
            'idCategoria' => $this->idCategoria,
            'nome' => $this->nome,
        ];


        // QUESTA FUNZIONE è PER LA FUNZIONE SHOW DOVE VISUALIZZO SOLO UN  CAMPO
        //return [
        //    'idCategoria' => 1,
        //    'nome' => "Azione"
        // ];
    }
}
