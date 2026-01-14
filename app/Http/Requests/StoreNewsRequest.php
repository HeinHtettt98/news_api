<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreNewsRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'required|string',
            'journalist_name' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp',
            'category_id' => 'required|exists:categories,id',
            'slug' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'description.required' => 'The description field is required.',
            'journalist_name.required' => 'The journalist name field is required.',
            'image.required' => 'The image field is required.',
            'image.image' => 'The image must be an image file.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, webp.',
        ];
    }
     protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
