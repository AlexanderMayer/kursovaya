<?php

namespace App\Http\Requests\Lot;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
