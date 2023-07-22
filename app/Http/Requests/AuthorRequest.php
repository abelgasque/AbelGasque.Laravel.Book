<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class AuthorRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:50',
            'last_name' => 'required|min:1|max:100',
            'email' => ['required', 'email', Rule::unique('authors', 'email')->ignore($this->author)],
        ];
    }
}
