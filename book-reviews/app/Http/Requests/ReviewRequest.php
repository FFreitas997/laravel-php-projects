<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string|max:500',
            'rating' => 'required|integer|between:1,5',
        ];
    }

    /**
     * Get the custom error messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'The :attribute is required.',
            'content.string' => 'The :attribute must be a string.',
            'content.max' => 'The :attribute may not be greater than :max characters.',
            'rating.required' => 'The :attribute is required.',
            'rating.integer' => 'The :attribute must be an integer.',
            'rating.between' => 'The :attribute must be between :min and :max.',
        ];
    }
}
