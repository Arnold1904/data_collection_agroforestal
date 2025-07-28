<?php

namespace App\Http\Requests\Post;

use GuzzleHttp\Promise\Create;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;// para ejecutar el form request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return (new CreatePostRequest())->rules(); // reutiliza las reglas de CreatePostRequest
    }
}
