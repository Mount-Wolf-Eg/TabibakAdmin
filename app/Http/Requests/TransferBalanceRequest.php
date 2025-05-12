<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferBalanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bank_name' => 'required|string|min:3|max:255',
            'iban'      => 'required|string|min:3|max:255',
        ];
    }
}
