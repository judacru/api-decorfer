<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|string',
            'cellphone' => 'required|string',
            'address' => 'required|string',
            'identification' => 'required|string',
            'email' => 'required|string',
            'special' => 'nullable|bool',
            'products' => 'required|array',
        ];
    }

    /**
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => __('messages.An error occurred while creating the user'),
            'errors' => $validator->errors(),
        ], 422));
    }
}
