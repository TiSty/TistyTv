<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UtenteResource extends JsonResource
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






    protected function getCampi()
    {
        // QUESTA FUNZIONE è PER LA FUNZIONE INDEX DOVE VISUALIZZO TUTTI I CAMPI
        return [
            'nome' => $this->nome,
            'cognome' => $this->cognome,
            "sesso" => $this->sesso,
            "dataNascita" => $this->dataNascita,
            "residenza" => $this->residenza,
            "domicilio" => $this->domicilio,
            "cittadinanza" => $this->cittadinanza,
            "ragioneSociale" => $this->ragioneSociale,
        ];
    }
}
