<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZakatAmilRequest extends FormRequest
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
        if($this->get('id')){
            $dusun = 'required|numeric|unique:zakat_amils,dusun,'.$this->get('id').',id,zakat_id,'.$this->get('zakat_id');
        }else{
            $dusun = 'required|numeric|unique:zakat_amils,dusun,NULL,id,zakat_id,'.$this->get('zakat_id');
        }
        return [
            'dusun' => $dusun,
            'beras' => 'required|numeric',
            'uang' => 'required|numeric',
        ];
    }
}
