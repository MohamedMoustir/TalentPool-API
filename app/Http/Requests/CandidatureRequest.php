<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidatureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'annonce_id' => 'required',
            'notes' => 'nullable|string',
            'cv' => 'required|file',
            'lettre_motivation' => 'required|file',
        ];
    }
}

