<?php

namespace App\Http\Requests\Feature;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFeatureRequest extends FormRequest
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
            'name' => ['required','string','max:60', 'min:3'],
            'description'=> ['string', 'max:500'],
            'links' => ['array'],
        ];
    }

    public function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(response()->json([
            'message'=> 'Invalid data',
            'errors'=> $validator->errors()
        ], 400));
    }
}
