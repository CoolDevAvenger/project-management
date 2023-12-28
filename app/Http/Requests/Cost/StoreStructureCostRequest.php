<?php

namespace App\Http\Requests\cost;

use App\Http\Requests\CoreRequest;

class StoreStructureCostRequest extends CoreRequest
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
        $rules = [
            'year' => 'required',
            'hour_number' => 'required|numeric',
            'estimate_employee_cost' => 'required|numeric',
            'estimate_hour_percent' => 'required|numeric'
        ];


        return $rules;
    }

}
