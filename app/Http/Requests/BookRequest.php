<?php

namespace App\Http\Requests;

class BookRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:100',
            'description' => 'required|min:1|max:500',
            'price' => 'required|numeric|min:0',
            'idAuthor' => 'required',
        ];
    }
}
