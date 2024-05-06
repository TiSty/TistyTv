<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Utente;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\v1\UtenteResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtenteController extends Controller
{
    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return Illuminated\Http\Response
     */
    public function show(Utente $utente)
    {
        if (Gate::allows("vedere")) {
            $risorsa = new UtenteResource($utente);
        } else {
            abort(403);
        }
        return $risorsa;
    }

    // public function id($id)
    // {
    //     if (Gate::allows("vedere")) {
    //         $utente = Utente::find($id);

    //         if ($utente) {
    //             return $utente->id;
    //         } else {
    //             return "Utente non trovato";
    //         }
    //     } else {
    //         return "Non hai il permesso di vedere questo utente";
    //     }
    // }
    public function id()
    {
        // Verifica se l'utente è autenticato
        if (Auth::check()) {
            // Restituisci l'ID dell'utente autenticato
            return Auth::id();
        } else {
            // Se l'utente non è autenticato, restituisci un messaggio di errore o gestisci il caso appropriato
            return "Utente non autenticato";
        }
    }
}
