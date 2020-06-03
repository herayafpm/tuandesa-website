<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SoalJawaban;

class SoalJawabanRequest extends FormRequest
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
      if($this->method() == 'PUT'){
        $bobot = SoalJawaban::where('id','!=',$this->get('id'))->where('jenis_bantuan_id',$this->get('jenis_bantuan_id'))->pluck('bobot');
      }else{
        $bobot = SoalJawaban::where('jenis_bantuan_id',$this->get('jenis_bantuan_id'))->pluck('bobot');
      }
      if($bobot){
        $bob = 100;
        foreach ($bobot as $b) {
          $bob -= (int) $b;
        }
        $bobot = $bob;
      }else{
        $bobot = 100;
      }
      return [
          'soal' => 'required',
          'jenis_bantuan_id' => 'required|numeric',
          'bobot' => 'required|numeric|max:'.$bobot,
          'tipe' => 'required'
      ];
    }
}
