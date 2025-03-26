<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branchname' =>'required',
            'description' =>'required',
            'contacperson' =>'required',
            'designation' =>'required',
            'mailingaddress' =>'required',
            'emailaddress' =>'required',
            'phone' =>'required',
            'active' =>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'branchname.required' =>'Branch name is not empty',
            'description.required' =>'Description is not empty',
            'contacperson.required' =>'Contact person is not empty',
            'designation.required' =>'Designation is not empty',
            'mailingaddress.required' =>'Mailing address is not empty',
            'emailaddress.required' =>'Email is not empty',
            'phone.required' =>'Phone is not empty',
            'active.required' =>'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'branchname' =>'Branch name',
            'description' =>'Description',
            'contacperson' =>'Contact Person',
            'designation' =>'Designation',
            'mailingaddress' =>'Mailing Address',
            'emailaddress' =>'Email Address',
            'phone' =>'Phone No',
            'active' =>'Status',
        ];
    }
}
