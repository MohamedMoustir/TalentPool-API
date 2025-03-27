<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'statut' => 'required|string|in:en_attente,en_cours,entretien,accepte,refuse',
        ];
    }
}