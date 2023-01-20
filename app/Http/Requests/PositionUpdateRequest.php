<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PositionUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required', "unique:positions,name,{$this->position->id}", 'max:256'],
        ];
    }
}
