<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CategoriaStoreRequest;
use App\Http\Requests\v1\CategoriaUpdateRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Resources\v1\CategoriaCollection;
use App\Http\Resources\v1\CategoriaResource;
use App\Http\Resources\v1\FilmCollection;
use App\Models\Films;
use Database\Seeders\categorie;
use Database\Seeders\Film;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return JsonResource
     */
    public function index()
    {

        if (Gate::allows('vedere')) {
            //   echo 'vedere';
            if (Gate::allows('Amministratore')) {
                $risorsa = Categoria::all();
            } else {
                $risorsa = Categoria::all();
            }

            // Aggiungi l'URL completo delle immagini a ciascuna categoria
            foreach ($risorsa as $categoria) {
                $categoria->src = asset('files/' . $categoria->src);  // Assicurati che $categoria->immagine contenga il nome del file dell'immagine
            }


            return new CategoriaCollection($risorsa);
        } else {
            abort(403, 'errore gate');
        }
    }


    /**
     * Store a newly created resource in storage.
     * 
     * @param Illuminated\Http\Request $request
     * @return Illuminated\Http\Response
     */

    public function store(CategoriaStoreRequest $request)
    {
        if (Gate::allows("creare")) {
            $dati = $request->validated(); // Verifica i dati
            $categoria = Categoria::create($dati); // Crea la categoria e memorizza i dati

            // Inizializza un array per il risultato
            $rit = ['data' => false];
            // Ritorna la risorsa Categoria nel caso il Gate consenta l'operazione

            // Verifica se sono presenti file
            if ($request->hasFile('imgCat')) {
                foreach ($request->file('imgCat') as $file) {
                    $name = time() . '.' . $file->getClientOriginalName();
                    $file->move(public_path() . '/files/', $name);
                    $categoria->src = $name;
                    $categoria->save();
                    // echo "nome" . $name;
                }
                $rit['data'] = true;
            }
            // Converte l'array in JSON e lo ritorna
            // echo json_encode($rit);
            return new CategoriaResource($categoria);
        } else {
            // Se il gate non consente l'operazione, restituisci un errore 404
            abort(404);
        }
    }



    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return Illuminated\Http\Response
     */
    public function show(Categoria $categoria)
    {
        if (Gate::allows("vedere")) {
            if (Gate::allows('Amministratore')) {
                $categoria->load('films');
                foreach ($categoria->films as $film) {
                    $film->src = asset('files/' . $film->src);
                }
                $risorsa = new CategoriaResource($categoria);
            } else {
                $risorsa = new CategoriaResource($categoria);
            }
        } else {
            abort(403);
        }
        return $risorsa;
    }


    public function showFilm($idCategoria)
    {
        if (Gate::allows("vedere")) {
            $categoria = Categoria::find($idCategoria);
            if (!$categoria) {
                abort(404, 'Categoria non trovata');
            } else {
                $films = $categoria->films()->get();

                // Modifica il percorso (src) di ciascun film
                foreach ($films as $film) {
                    $film->src = asset('files/' . $film->src);
                }

                // Restituisci i film modificati come collezione di risorse
                return new FilmCollection($films);
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
    public function update(CategoriaUpdateRequest $request, Categoria $categoria)
    {
        if (Gate::allows("modificare")) {
            //prelevare i dati -> sono nella $request

            //verificare i dati
            $dati = $request->validated();  //variabile = la request+funzione
            $categoria->fill($dati);
            $categoria->save();
           
            //inserirli nell'oggetto al database preparare model

            if ($request->hasFile('imgCat')) {
                $file=$request->file('imgCat');
                
                $name = time() . '.' . $file->getClientOriginalName();
              
                $file->move(public_path() . '/files/', $name);
                    $categoria->src = $name;
                    $categoria->save();
              
            }

            return new CategoriaResource($categoria); //ritorna la risorsa modificata
        } else {
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        if (Gate::allows("eliminare")) {
            $categoria->deleteOrFail();
            return response()->noContent();
        } else {
            abort(403);
        }
    }
}
