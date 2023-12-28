<?php

namespace App\Http\Requests;

use App\Traits\CustomFieldsRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreEstimate extends FormRequest
{
    use CustomFieldsRequestTrait;

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
            'estimate_number' => 'required|unique:estimates,estimate_number,' . $this->route('estimate').',id,company_id,' . company()->id,
            'client_id' => 'required',
            'project_category_id' => 'required',
            'project_sub_category_id' => 'required',
            'valid_till' => 'required',
            'sub_total' => 'required',
            'total' => 'required',
            'currency_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            // 'payment_method' => 'required',
            // 'payment_type' => 'required'
        ];

        $rules = $this->customFieldRules($rules);

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        $attributes = $this->customFieldsAttributes($attributes);

        return $attributes;
    }

    public function messages()
    {
        return [
            'client_id.required' => __('modules.projects.selectClient')
        ];
    }

}
