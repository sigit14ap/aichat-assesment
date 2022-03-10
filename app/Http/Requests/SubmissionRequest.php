<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;


class SubmissionRequest extends FormRequest
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
        return [
            'image' =>  'required|image'
        ];
    }

    /**
     * Custom response validation
     */
    protected function failedValidation(Validator $validator)
    {
        $response = Response::validationResponse('Bad Request', $validator->errors()->toArray());

        throw new ValidationException($validator, $response);
    }

}
