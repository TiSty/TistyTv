<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class modificaDatiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "nome" => "required|string|max:45"   , 
            "cognome" => "required|string|max:85"  ,  
            "sesso" => "required|integer"    ,
            "idRuoloUtente"=>"Utente",
            "dataNascita" => "required|date"    ,
            "cittadinanza" => "required|string",
            "residenza" => "required|string",
            "ragioneSociale" => "required|string",
            "domicilio" => "required|string"
         
          
        ];
    }
}
