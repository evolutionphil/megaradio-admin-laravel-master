<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RadioStationRequest extends FormRequest
{
    /**
     * @param  string  $errorBag
     */
    public function setErrorBag(string $errorBag): void
    {
        $this->errorBag = $errorBag;
    }

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
            'name' => 'required|max:255',
            'url' => 'required|string',
            'countrycode' => 'max:2',
        ];
    }
}
