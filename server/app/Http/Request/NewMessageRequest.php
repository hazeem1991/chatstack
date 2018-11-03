<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use JWTAuth;
/**
 * simple laravel Request Class for some Validation
 */
class NewMessageRequest extends FormRequest
{
    protected $forceJsonResponse = true;
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'to'=>'required',
            'message'=>'required|profanity|max:500|min:1'
        ];
    }
    public function attributes()
    {
        return [
            'to'=>'The Reciver',
            'message'=>'Message'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if($this->forceJsonResponse)
        {
            $helper = app()->make('helper');
            $log['method']=$this->method();
            $log['response']=json_encode(['errors' => $validator->errors()],JSON_UNESCAPED_UNICODE);
            $log['request']=json_encode($this->except(["token"]),JSON_UNESCAPED_UNICODE);
            $log['user']=$this->user()->email;
            $helper->log($log);
            
            throw new HttpResponseException(
                response()->json(['errors' => $validator->errors()],422,[],JSON_UNESCAPED_UNICODE)
            );
        }
    }
}
