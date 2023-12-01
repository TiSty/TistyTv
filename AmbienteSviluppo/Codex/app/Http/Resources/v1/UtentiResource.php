<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UtentiResource extends JsonResource
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
         'nome' => $this->nome,
         'cognome' => $this->cognome ,
         "sesso" => $this->sesso ,
        //  "idStato" => $this->idStato ,
         "cittadinanza" => $this->cittadinanza ,
         "dataNascita" => $this->dataNascita ,
        //  "credito" => $this->credito ,
    
        ];


        // QUESTA FUNZIONE è PER LA FUNZIONE SHOW DOVE VISUALIZZO SOLO UN  CAMPO
        //return [
         //"nome"=> "Mattia",
        // "cognome"=> "Cacciatore",
        // "sesso"=> "M" ,
        // "cittadinanza"=> "Ita",
        // "dataNascita"=> "1998-08-24", 
        // "credito"=> 0,
        //];
    }
}
