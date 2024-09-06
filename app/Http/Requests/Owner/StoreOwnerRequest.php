<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class StoreOwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "cpf" => "required|string",
            "number" => "required|string",
            "birth_date" => "required|date",
            "email" => "required|email|string",
            "tower" => "required|integer",
            "apartment_number" => "required|string",
            "garage" => "required|string",
            "rented" => "required|numeric",
            "paid" => "required|numeric"
        ];
    }
}