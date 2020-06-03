<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeritaImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
    public function messages()
    {
        return [
            'image.required' => "Gambar tidak boleh kosong",
            'image.*.image' => "Yang anda masukkan bukan gambar",
            'image.*.mimes' => "Jenis file bukan salah satu dari jpeg,png,jpg,gif,svg",
            'image.*.max' => "File yang diupload tidak boleh lebih dari 2MB"
        ];
    }
}
