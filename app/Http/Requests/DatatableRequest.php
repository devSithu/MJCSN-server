<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatatableRequest extends FormRequest
{

    public function rules()
    {

        return [
            'columns' => "array",
            'columns.*.searchable' => "required",
            'columns.*.data' => "required",
            'order' => "array",
            'order.*.column' => "required",
            'order.*.dir' => "required",
            'start' => 'integer|min:0',
            'length' => 'integer|min:0',
        ];
    }

}
