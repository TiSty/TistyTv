<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EpisodiResource extends JsonResource
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
         'idEpisodio' => $this->idEpisodio,
         'titolo' => $this->titolo,
         'serieTv'=> $this->serieTv,
         'episodio'=> $this->episodio,
         'stagione'=>$this->stagione,
         "durata" => $this->durata,
         "datiEp" => $this->datiEp,
         "anno" => $this->anno,
         "trama" => $this->trama,
         "trailer" => $this->trailer,
         "src" => $this->src
        ];


        // QUESTA FUNZIONE è PER LA FUNZIONE SHOW DOVE VISUALIZZO SOLO UN  CAMPO
        //return [
        //    "idEpisodio"=> 1,
        //    "titolo" => "I giorni andati",
        //    "serieTv" => "The Walking Dead",
        //    "episodio"=> 1,
        //    "stagione"=> 1,
        //    "durata"=> 60,
        //    "anno"=> "2010",
        //    "trama"=> "L'agente di polizia Rick si risveglia dal coma e intraprende la ricerca della propria famiglia. Morgan e Duane cercano di insegnargli alcune basilari regole di sopravvivenza."
        //];
    }
}
