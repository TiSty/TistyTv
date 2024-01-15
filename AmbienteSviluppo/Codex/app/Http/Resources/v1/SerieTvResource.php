<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SerieTvResource extends JsonResource
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
    

    protected function getCampi(){
        // QUESTA FUNZIONE è PER LA FUNZIONE INDEX DOVE VISUALIZZO TUTTI I CAMPI
        return [
         "idSerieTv" => $this->idSerieTv,
         "titolo" => $this->titolo,
         "durata" => $this->durata,
         "episodi" => $this->episodi,
         "stagioni" => $this->stagioni,
         "regista" => $this->regista,
         "categoria" => $this->categoria,
         "anno" => $this->anno,
         "trama" => $this->trama,
         "trailer" => $this->trailer,
         "src" => $this->src
        ];
    }
}
        