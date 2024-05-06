<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\AppHelpers;
use App\Models\Films;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\FilmStoreRequest;
use App\Http\Requests\v1\FilmUpdateRequest;
use App\Http\Resources\v1\FilmCollection;
use App\Http\Resources\v1\FilmResource;
use App\Http\Resources\v1\FilmSingoloResource;
use App\Http\Requests\v1\FilmVisualizzatoUpdateRequest;

use App\Models\Categoria;
use Database\Seeders\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FilmController extends Controller
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
                $risorsa = Films::all();
            } else {
                $risorsa = Films::all();
            }
            // Aggiungi l'URL completo delle immagini a ciascuna categoria
            foreach ($risorsa as $film) {
                $film->src = asset('files/' . $film->src);  // Assicurati che $categoria->immagine contenga il nome del file dell'immagine
                $film->trailer = asset('files/' . $film->trailer);
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
    public function store(FilmStoreRequest $request)
    {
        if (Gate::allows("creare")) {
            $dati = $request->validated();     //verificare i dati
            $film = Films::create($dati);     // creo i dati (model = alla classe del model:metodo per crare i dati) e li metto dentro la variabile

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
                $film->trailer = $fileNames[0]; // Supponendo che ci sia solo un trailer per film
                $film->src =  $fileNames[1]; // Concatena i nomi dei file separati da virgola
                $film->save();
                // $rit['data'] = true;
            } else {
                $rit['data'] = false;
            }
            // Converte l'array in JSON e lo ritorna
            // echo json_encode($rit);
            return new FilmResource($film);    // ritorna una nuova istanza resource con la risorsa creata
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
    public function show(Films $film)
    {
        if (Gate::allows("vedere")) {
            if (Gate::allows('Amministratore')) {
                $film->trailer = asset('files/' . $film->trailer);
                $film->src = asset('files/' . $film->src);  // Assicurati che $categoria->immagine contenga il nome del file dell'immagine
                $risorsa = new FilmResource($film);
            } else {
                $risorsa = new FilmResource($film);
            }
        } else {
            abort(403);
        }
        return $risorsa;
    }

    public function showCat($idCategoria)
    {
        if (Gate::allows("vedere")) {
            $categoria = Categoria::find($idCategoria);
            if (!$categoria) {
                abort(404, 'Categoria non trovata');
            } else {
                $film = $categoria->Films()->get();

                return AppHelpers::rispostaCustom($film);
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
    public function update(FilmUpdateRequest $request, Films $film)
    {
        if (Gate::allows("modificare")) {
            //prelevare i dati -> sono nella $request
            // print_r($_POST);
            //verificare i dati
            $dati = $request->validated();  //variabile = la request+funzione
            //inserirli nell'oggetto al database preparare model
            $film->fill($dati); //prendo il model -> inserisco i dati nella variabile
            //salvarlo
            // print_r($dati);
            $film->save(); //prendo il model e lo salvo
            //ritornare la risorsa modificata

            if ($request->hasFile('filesDaCaricare')) {
                foreach ($request->file('filesDaCaricare') as $file) {
                    $name = time() . '.' . $file->getClientOriginalName();
                    $file->move(public_path() . '/files/', $name);
                    $fileNames[] = $name;
                }
                $film->trailer = $fileNames[0]; // Supponendo che ci sia solo un trailer per film
                $film->src =  $fileNames[1]; // Concatena i nomi dei file separati da virgola
                $film->save();
            }
            return new FilmResource($film); //ritorna la risorsa modificata
        } else {
            abort(403);
        }
    }
    public function  modificaSingola(FilmVisualizzatoUpdateRequest $request, Films $film)
    {
        if (Gate::allows("modificare")) {
            //prelevare i dati -> sono nella $request

            //verificare i dati
            $dati = $request->validated();
            //inserirli nell'oggetto al database preparare model
            $film->fill($dati);
            //salvarlo
            $film->save();
            //ritornare la risorsa modificata
            return new FilmSingoloResource($film);
        } else {
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Films $film)
    {
        if (Gate::allows("eliminare")) {
            $film->deleteOrFail();
            return response()->noContent();
        } else {
            abort(403);
        }
    }
}
