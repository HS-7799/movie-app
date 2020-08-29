<?php

namespace App\Http\Requests\actor;

use Illuminate\Foundation\Http\FormRequest;

class CreateActorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->abilities()->contains('create_actor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:actors',
            'biographie' => 'required',
            'photo' => 'required|file|image',
        ];
    }
}
