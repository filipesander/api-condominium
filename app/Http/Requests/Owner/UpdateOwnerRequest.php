<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOwnerRequest extends FormRequest
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
                "name" => "string",
                "cpf" => "string",
                "number" => "string",
                "birth_date" => "date",
                "email" => "string",
                "tower" => "integer",
                "apartment_number" => "string",
                "garage" => "string",
                "rented" => "numeric",
                "paid" => "numeric"
        ];
    }
}