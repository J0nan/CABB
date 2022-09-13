<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class AuthValidator 
{

    public static function register($data)
    {
        return Validator::make($data,[
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required',
            'wallet'    => 'required|string'
        ],[
            'name.required'         => 'name.Field_must_not_be_empty',
            'email.required'        => 'email.Field_must_not_be_empty',
            'email.email'           => 'email.Field_must_be_a_valid_email',
            'email.unique'          => 'email.Email_already_in_use',
            'password.required'     => 'password.Field_must_not_be_empty',
            'wallet.required'       => 'wallet.Field_must_not_be_empty',
            'wallet.string'         => 'wallet.Field_must_be_a_string',
        ]);
    }

    public static function logIn($data)
    {
        return Validator::make($data,[
            'email'     => 'required|email',
            'password'  => 'required'
        ],[
            'email.required'        => 'email.Field_must_not_be_empty',
            'email.email'           => 'email.Field_must_be_a_valid_email',
            'password.required'     => 'password.Field_must_not_be_empty',
        ]);
    }

    public static function refreshToken($data)
    {
        return Validator::make($data,[
            'refresh_token'     => 'required',
        ],[
            'refresh_token.required'   => 'refresh_token.Field_must_not_be_empty',
        ]);
    }
}