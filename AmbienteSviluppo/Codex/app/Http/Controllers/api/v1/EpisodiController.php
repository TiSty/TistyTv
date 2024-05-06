<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\AppHelpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\EpisodiCollection;
use App\Http\Requests\v1\EpisodiStoreRequest;
use App\Http\Requests\v1\EpisodiUpdateRequest;
use App\Http\Resources\v1\EpisodiResource;
use App\Http\Resources\v1\SerieTvResource;
use Illuminate\Http\Request;
use App\Models\Episodio;
use App\Models\SeriesTv;
use Database\Seeders\SerieTv;
use Illuminate\Support\Facades\Gate;

class EpisodiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows("vedere")) {
            if (Gate::allows('Amministratore')) {
                $risorsa = Episodio::all();
            } else {
                $risorsa = Episodio::all();
            }
            return new EpisodiCollection($risorsa);
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Illuminated\Http\Request $request
     * @return Illuminated\Http\Response
     */
    public function store(EpisodiStoreRequest $request)
    {
        if (Gate::allows("creare")) {
            $dati = $request->validated();                   //verificare i dati (creo una variabile dati = variabile request -> validated())
            $episodio = Episodio::create($dati);            // creo i dati ( variabile = model::metodo per crare i dati) e li metto dentro la variabile
            return new EpisodiResource($episodio);          // ritorna una nuova istanza resource con la risorsa creata
        } else {
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return Illuminated\Http\Response
     */
    public function show(Episodio $episodio)
    {
        if (Gate::allows("vedere")) {
            if (Gate::allows('Amministratore')) {
                $episodio->trailer = asset('files/' . $episodio->trailer);
                $episodio->src = asset('files/' . $episodio->src);  // Assicurati che $categoria->immagine contenga il nome del file dell'immagine
                $risorsa = new EpisodiResource($episodio);
            } else {
                $risorsa = new EpisodiResource($episodio);
            }
        } else {
            abort(403);
        }
        return $risorsa; //verificare se da usare con AppHelpers::rispostaCustom()
    }


    public function showEpisodi($idEpisodio)
    {
        if (Gate::allows("vedere")) {
            $episodio = Episodio::find($idEpisodio);
            $id = $episodio->idSerieTv;

            $serieTv = SeriesTv::find($id);
            if (!$serieTv) {
                abort(404, 'Serie TV non trovata');
            } else {
                // Ottenere gli episodi correlati alla Serie TV
                $episodi = $serieTv->episodi()->get();

                return new EpisodiCollection($episodi);
            }
        }
    }

    // public function showEpisodio($idSerieTv)
    // {
    //     if (Gate::allows("vedere")) {
    //         $serieTv = SeriesTv::find($idSerieTv);

    //         if (!$serieTv) {
    //             abort(404, 'Serie TV non trovata');
    //         } else {
    //             // Ottenere gli episodi correlati alla Serie TV
    //             $episodi = $serieTv->episodi()->get();
    //             return new EpisodiCollection($episodi);
    //         }
    //     }
    // }

    public function showEpisodio($idSerieTv)
    {
        // Controlla se l'utente ha il permesso di vedere gli episodi
        if (!Gate::allows("vedere")) {
            abort(403, 'Accesso non autorizzato');
        }
        // Trova la serie TV
        $serieTv = SeriesTv::find($idSerieTv);
        // Se la serie TV non esiste, restituisci un errore 404
        if (!$serieTv) {
            abort(404, 'Serie TV non trovata');
        }
        // Ottenere gli episodi correlati alla Serie TV
        $episodi = $serieTv->episodi()->get();
        // Modifica i campi trailer e src di ciascun episodio
        foreach ($episodi as $episodio) {
            $episodio->trailer = asset('files/' . $episodio->trailer);
            $episodio->src = asset('files/' . $episodio->src);
        }
        // Restituisci gli episodi modificati come risorsa
        return EpisodiResource::collection($episodi);
    }

    public function showEpisodiDaEpisodio($idEpisodio)
    {
        // Controlla se l'utente ha il permesso di vedere
        if (Gate::allows("vedere")) {
            // Ottieni l'id della serie TV a cui appartiene l'episodio
            $idSerieTv = Episodio::getIdSerieTvFromEpisodio($idEpisodio);

            // Verifica se l'ID della serie TV è stato trovato
            if ($idSerieTv !== null) {
                // Ottieni tutti gli episodi della serie TV
                $episodi = Episodio::where('serieTv', $idSerieTv)->get();

                // Ritorna i dati per l'utilizzo successivo nel controller o nella vista
                return AppHelpers::rispostaCustom($episodi);
            } else {
                // Gestisci il caso in cui l'episodio non è stato trovato
                return response()->json(['error' => 'Episodio non trovato'], 404);
            }
        } else {
            // Gestisci il caso in cui l'utente non ha il permesso di vedere
            return response()->json(['error' => 'Non sei autorizzato a vedere gli episodi'], 403);
        }
    }



    /**
     * Update the specified resource in storage.
     * 
     * @param Illuminated\Http\Request $request
     * @param int $id
     * @return Illuminated\Http\Response
     */
    public function update(EpisodiUpdateRequest $request, Episodio $episodio)
    {
        if (Gate::allows("modificare")) {
            //prelevare i dati -> sono nella $request


            $dati = $request->validated(); //verificare i dati
            //inserisco i dati nell'oggetto al database,  preparazione del model
            $episodio->fill($dati);        //creo una variabile usa la funzione per inserire i dati questa variabile si chiama model
            //salvarlo
            $episodio->save(); //prendo il model e lo salvo
            //ritornare la risorsa modificata
            if ($request->hasFile('filesDaCaricare')) {
                foreach ($request->file('filesDaCaricare') as $file) {
                    $name = time() . '.' . $file->getClientOriginalName();
                    $file->move(public_path() . '/files/', $name);
                    $fileNames[] = $name; 
                }
                $episodio->trailer = $fileNames[0]; // Supponendo che ci sia solo un trailer per epi$episodio
                $episodio->src =  $fileNames[1]; // Concatena i nomi dei file separati da virgola
                $episodio->save();
            }


            return new EpisodiResource($episodio); //ritorna una nuova istanza resource con la risorsa modificata
        } else {
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Episodio $episodio)
    {
        if (Gate::allows("eliminare")) {
            $episodio->deleteOrFail();
            return response()->noContent();
        } else {
            abort(403);
        }
    }
}
