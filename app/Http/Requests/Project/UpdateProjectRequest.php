<?php

namespace App\Http\Requests\Project;

use App\Enums\UserProjectRole;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;


class UpdateProjectRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:50', 'min:3'],
            'description' => ['sometimes' ,'nullable','string', 'max:255'],
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
