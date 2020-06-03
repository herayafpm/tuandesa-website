<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SoalJawaban;

class JawabanRequest extends FormRequest
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
          'jawaban' => 'required',
          'nilai' => 'required|numeric|max:'.env('COUNT_NILAI'),
          'soal_jawaban_id' => 'required|numeric'
      ];
    }
}
