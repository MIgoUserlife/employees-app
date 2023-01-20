<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PositionStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required', "unique:positions", 'max:256'],
        ];
    }
}
