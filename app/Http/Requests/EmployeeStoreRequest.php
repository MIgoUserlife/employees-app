<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class EmployeeStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $salary = Str::replace(',', '', $this->request->get('salary'));
        $this->request->set('salary', (int)($salary));

        $phone_number = str_replace([' ', '_', '(', ')'], '', $this->request->get('phone_number'));
        $this->request->set('phone_number', $phone_number);

        return [
            'name' => ['string', 'required', 'unique:employees', 'min:2', 'max:256'],
            'position_id' => ['integer', 'required'],
            'date_of_employment' => ['required', 'date_format:d.m.y', 'before:tomorrow'],
            'phone_number' => ['required', 'regex:/^\+380[0-9]{9}$/'],
            'email' => ['email:rfc,dns', 'required', 'unique:employees'],
            'salary' => ['integer', 'required', 'min:0', 'max:500000'],
            'photo' => ['image', 'nullable', 'mimes:png,jpg', 'size:5120', 'dimensions:min_width=300,min_height=300'],
        ];

    }

    public function messages()
    {
        return [
            'position_id.required' => 'The position field is required.',
        ];
    }
}
