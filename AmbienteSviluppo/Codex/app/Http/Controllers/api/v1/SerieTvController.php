<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\AppHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\SerieTvStoreRequest;
use App\Http\Requests\v1\SerieTvUpdateRequest;
use App\Http\Requests\v1\SerieTvVisualizzataUpdateRequest;
use App\Http\Resources\v1\SerieTvCollection;
use App\Http\Resources\v1\SerieTvResource;
use App\Http\Resources\v1\SerieTvSingolaResource;
use App\Models\Categoria;
use App\Models\SeriesTv;
use Database\Seeders\SerieTv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SerieTvController extends Controller 
{
    /**
     * Display a listing of the resource.
     * 
     * @return JsonResource
     */
    public function index()
    {
        if (Gate::allows("vedere")) {
            if (Gate::allows('Amministratore')) {
                $risorsa = SeriesTv::all();
            } else {
                $risorsa =  SeriesTv::all();
            }
              // Aggiungi l'URL completo delle immagini a ciascuna categoria
              foreach ($risorsa as $serieTv) {
                $serieTv->src = asset('files/' . $serieTv->src);  // Assicurati che $categoria->immagine contenga il nome del file dell'immagine
                $serieTv->trailer = asset('files/' . $serieTv->trailer);
            }
            return AppHelpers::rispostaCustom($risorsa);
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
    public function store(SerieTvStoreRequest $request)
    {
        if (Gate::allows("creare")) {
            $dati = $request->validated();     //verificare i dati
            $serieTv = SeriesTv::create($dati);     // creo i dati (model = alla classe del model:metodo per crare i dati) e li metto dentro la variabile

            $rit = ['data' => false];

            foreach ($request->file('filesDaCaricare') as $file) {
                if ($file->getSize() > 2 * 1024 * 1024 * 1024) { // 2048 KB convertiti in byte
                    $rit['data'] = false;
                    $rit['message'] = 'Dimensione del file troppo grande. La dimensione massima consentita è 2048 KB.';
                    return json_encode($rit);
                }
            }
            if ($request->hasFile('filesDaCaricare')) {
                foreach ($request->file('filesDaCaricare') as $file) {
                    $name = time() . '.' . $file->getClientOriginalName();
                    $file->move(public_path() . '/files/', $name);
                    $fileNames[] = $name; 
                }
                $serieTv->trailer = $fileNames[0]; // Supponendo che ci sia solo un trailer per serieTv
                $serieTv->src =  $fileNames[1]; // Concatena i nomi dei file separati da virgola
                $serieTv->save();
                // $rit['data'] = true;
            } else {
                $rit['data'] = false;
            }
            // Converte l'array in JSON e lo ritorna
            // echo json_encode($rit);
            return new SerieTvResource($serieTv);    // ritorna una nuova istanza resource con la risorsa creata
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
    public function show(SeriesTv $serieTv)
    {
        if (Gate::allows("vedere")) {
            if (Gate::allows('Amministratore')) {
                $risorsa = new SerieTvResource($serieTv);
                $serieTv->trailer = asset('files/' . $serieTv->trailer);
                $serieTv->src = asset('files/' . $serieTv->src);  // Assicurati che $categoria->immagine contenga il nome del file dell'immagine
                $risorsa = new SerieTvResource($serieTv);
            } else {
                $risorsa =  new SerieTvResource($serieTv);
            }
        } else {
            abort(403);
        }
        return $risorsa;
    }


    public function showCat($idCategoria){
        if(Gate::allows("vedere")){
            $categoria = Categoria::find($idCategoria);
            if(!$categoria){
                abort(404, 'Categoria non trovata');
            } else {
                $serieTv = $categoria->SeriesTv()->get();

                return AppHelpers::rispostaCustom($serieTv);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Illuminated\Http\Request $request
     * @param int $id
     * @return Illuminated\Http\Response
     */
 
    public function update(SerieTvUpdateRequest $request, Seriestv $serieTv)
    {
        if (Gate::allows("modificare")) {
            //prelevare i dati -> sono nella $request

            //verificare i dati
            $dati = $request->validated();  //variabile = la request+funzione
            //inserirli nell'oggetto al database preparare model
            $serieTv->fill($dati); //prendo il model -> inserisco i dati nella variabile
            //salvarlo
            $serieTv->save(); //prendo il model e lo salvo
            //ritornare la risorsa modificata

            if ($request->hasFile('filesDaCaricare')) {
                foreach ($request->file('filesDaCaricare') as $file) {
                    $name = time() . '.' . $file->getClientOriginalName();
                    $file->move(public_path() . '/files/', $name);
                    $fileNames[] = $name; 
                }
                $serieTv->trailer = $fileNames[0]; // Supponendo che ci sia solo un trailer per serie$serieTv
                $serieTv->src =  $fileNames[1]; // Concatena i nomi dei file separati da virgola
                $serieTv->save();
            }
            return new SerieTvResource($serieTv); //ritorna la risorsa modificata
        } else {
            abort(403);
        }
    }
    
    public function modificaSingola(SerieTvVisualizzataUpdateRequest $request, Seriestv $serieTv)
    {
        if (Gate::allows("modificare")) {
            //prelevare i dati -> sono nella $request

            //verificare i dati
            $dati = $request->validated();
            //inserirli nell'oggetto al database preparare model
            $serieTv->fill($dati);
            //salvarlo
            $serieTv->save();
            //ritornare la risorsa modificata
            return new SerieTvSingolaResource($serieTv);
        } else {
            abort(403);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeriesTv $serieTv)
    {
        if (Gate::allows("eliminare")) {
            $serieTv->deleteOrFail();
            return response()->noContent();
        } else {
            abort(403);
        }
    }
}
