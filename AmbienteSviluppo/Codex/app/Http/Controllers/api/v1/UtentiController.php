<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\AppHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\UtenzaRequest;
use App\Http\Requests\v1\modificaDatiRequest;
use App\Http\Requests\v1\UtentiStoreRequest;
use App\Http\Requests\v1\UtentiUpdateRequest;
use App\Http\Resources\v1\UtentiCollection;
use App\Http\Resources\v1\UtentiResource;
use App\Models\Utente;

use App\Models\ContattoAuth;
use App\Models\PasswordUtente;
use App\Models\User;
use App\Models\utente_ruoloUtente;
use Database\Seeders\utenti_ruoliUtente;

class UtentiController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return JsonResource
     */
    public function index()
    {
        $risorsa = Utente::all();
        $ritorno = new UtentiCollection($risorsa);
        return $ritorno;
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Illuminated\Http\Request $request
     * @return Illuminated\Http\Response
     */
    public function store(UtentiStoreRequest $request)
    {
        $dati = $request->validated(); //verificare i dati
        $utente = Utente::create($dati); // creo i dati (model = alla classe del model:metodo per crare i dati) e li metto dentro la variabile
        return new UtentiResource($utente); // ritorna una nuova istanza resource con la risorsa creata
    }

    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return Illuminated\Http\Response
     */
    public function show(Utente $utente)
    {

        $risorsa = new UtentiResource($utente);
        return $risorsa;
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Illuminated\Http\Request $request
     * @param int $id
     * @return Illuminated\Http\Response
     */
    public function update(UtentiUpdateRequest $request, Utente $utente)
    {
        //prelevare i dati -> sono nella $request

        //verificare i dati
        $dati = $request->validated();
        //inserirli nell'oggetto al database preparare model
        $utente->fill($dati);
        //salvarlo
        $utente->save();
        //ritornare la risorsa modificata
        return new UtentiResource($utente);
    }


    public function destroy(Utente $utente)
    {
        $utente->deleteOrFail();
        return response()->noContent();
    }



    public function aggiungiCredito($idUtente, $importo)
    {
        $utente = Utente::findOrFail($idUtente);
        $nuovoCredito = $utente->credito + $importo;
        $utente->credito = $nuovoCredito;
        $utente->save();

        return response()->json(['message' => 'credito aggiunto']);
    }

    public function creaUtente(modificaDatiRequest $request)
    {
        //verifico i dati
        $data = $request->validated();

        //creo l'utente
        $utente = new Utente();
        $utente = Utente::create($data);

        $hashRes = hash('sha512', $request->input('residenza'));
        $hashDom = hash('sha512', $request->input('domicilio'));

        $utente->nome = $request->input('nome');
        $utente->cognome = $request->input('cognome');
        $utente->sesso = $request->input('sesso');
        $utente->dataNascita = $request->input('dataNascita');
        $utente->cittadinanza = $request->input("cittadinanza");
        $utente->residenza = $hashRes;
        $utente->domicilio = $hashDom;
        $utente->ragioneSociale = $request->input('ragioneSociale');

        $utente->save();

        //estrapolo l'id appena creato 
        $idUtente = $utente->idUtente;
        //salvo l'idUtente nella sessione
        session(['idUtente' => $idUtente]);

        // return response()->json(['message' => 'Utente creato!', 'idUtente' => $idUtente, $utente]);
        return AppHelpers::rispostaCustom($idUtente, 'Utente Creato!');
    }

    public function creaUtenza(UtenzaRequest $request)
    {
        $utenteAuth = new ContattoAuth();
        $password = new PasswordUtente();
        $utente_ruoloUtente = new utente_ruoloUtente();


        $data = $request->validated();
        // print_r($data);

        //estrapolo l'id appena creato 
        $idUtente = session('idUtente');

        $hashUser =  $request->input('user');
        $hashPassword = $request->input('password');
        $hashSale = $request->input('sale');


        $utenteAuth->idUtente = $request->input('idUtente');
        $utenteAuth->user = $hashUser;

        $utente_ruoloUtente->idUtente = $request->input('idUtente');
        // $utente_ruoloUtente->idRuoloUtente = $request->input('idRuoloUtente');

        $password->idUtente = $request->input('idUtente');
        $password->psw = $hashPassword;
        $password->sale = $hashSale;


        $utenteAuth->sfida = $request->input('sfida');
        $utenteAuth->secretJWT = $request->input('secretJWT');
        $utenteAuth->inizioSfida = $request->input('inizioSfida');

        $utenteAuth->save();
        $password->save();
        $utente_ruoloUtente->save();

        return response()->json(['message' => 'Credenziali create!', 'utenteAuth' => $utenteAuth, 'idUtente' => $idUtente]);
        // return AppHelpers::rispostaCustom($idUtente, 'Credenziali create!');
    }
}


//SERVE PER COLLEGARE I POTERI CON L'IDUTENTE
