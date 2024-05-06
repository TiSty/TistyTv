<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class FilmStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    //public function authorize(): bool
    //{
    //    return false;
    //}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    { //metto i campi della tabella da creare
        return [
        'titolo'=> 'string|max:80',
        'durata'=> 'integer',
        'regista'=> 'string|max:80',
        'categoria' => 'string|max:80',
        'anno'=> 'integer',
        'trama'=> 'string'
        ];
    }
}


