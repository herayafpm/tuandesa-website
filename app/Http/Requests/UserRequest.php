<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if($this->method() == "PUT"){
            $username = 'required|unique:users,username,'.$this->get('id');
            $email = 'required|email|unique:users,email,'.$this->get('id');
        }else{
            $username = 'required|unique:users,username';
            $email = 'required|email|unique:users,email';
        }
        return [
            'name' => 'bail|required|min:2',
            'ttl' => 'required',
            'address' => 'required',
            'no_hp' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'username' => $username,
            'email' => $email,
            'password' => 'sometimes|min:6',
            'roles' => 'required|min:1'
        ];
    }
}
