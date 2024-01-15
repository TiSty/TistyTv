<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class FilmVisualizzatoUpdateRequest extends FormRequest
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
    {
        return [
            'titolo'=> 'nullable|string|max:80',
            'durata'=> 'nullable|integer',
            'regista'=> 'nullable|string|max:80',
            'categoria' => 'nullable|string|max:80',
            'anno'=> 'nullable|integer',
            'trama'=> 'nullable|string',
            'src'=> 'nullable|string',
            'trailer'=> 'nullable|string',

        ];
    }
}
