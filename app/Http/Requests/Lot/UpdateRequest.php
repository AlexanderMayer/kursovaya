<?php

namespace App\Http\Requests\Lot;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title'=>'string',
            'description'=>'string',
            'start_cost'=>'integer',
            'bet_step'=>'integer',
            'category_id'=>'integer',
        ];
    }
}
