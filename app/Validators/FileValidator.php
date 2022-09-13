<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class FileValidator 
{

    public static function store($data)
    {
        return Validator::make($data,[
            'file'      => 'required|file',
            'name'      => 'required|string',
            'surname'   => 'required|string',
            'legalId'   => 'required|string',
        ],[
            'file.required'       => 'file.Field_must_not_be_empty',
            'file.file'           => 'file.Field_must_be_a_file',
            'name.required'       => 'name.Field_must_not_be_empty',
            'name.string'         => 'name.Field_must_be_a_string',
            'surname.required'    => 'surname.Field_must_not_be_empty',
            'surname.string'      => 'surname.Field_must_be_a_string',
            'legalId.required'    => 'legalId.Field_must_not_be_empty',
            'legalId.string'      => 'legalId.Field_must_be_a_string',
        ]);
    }

    public static function verify($data)
    {
        return Validator::make($data,[
            'file'          => 'required|file',
        ],[
            'file.required'             => 'file.Field_must_not_be_empty',
            'file.file'                 => 'file.Field_must_be_a_file',
        ]);
    }
}