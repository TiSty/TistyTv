<?php

namespace App\Http\Requests\v1;

use App\Models\Episodio;
use Illuminate\Foundation\Http\FormRequest;

class EpisodiUpdateRequest extends FormRequest
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
            'titolo'=> 'string|max:80',
            'durata'=> 'integer',
            'serieTv'=> 'string|max:80',
            'anno'=> 'integer',
            'trama'=> 'string',
            'stagione' => 'string|max:80',
            'episodio' =>'string|max:80',
            
            
        ];
            
    }
}
