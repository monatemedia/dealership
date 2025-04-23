<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreCarRequest extends FormRequest
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
            'manufacturer_id' => 'required|exists:manufacturer,id',
            'model_id' => 'required|exists:models,id',
            'year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'price' => 'required|integer|min:0',
            'vin' => 'required|string|size:17',
            'mileage' => 'required|integer|min:0',
            'car_type_id' => 'required|exists:car_types,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
            'phone' => 'required|string|min:10',
            'description' => 'nullable|string',
            'published_at' => 'nullable|string',
            'features' => 'array',
            'features.*' => 'string',
            'images' => 'array',
            'images.*' => File::image()
                ->max(2048)
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required',
            'manufacturer_id.required' => 'Please Select Manufacturer'
        ];
    }

    public function attributes()
    {
        return [
            'manufacturer_id' => 'manufacturer',
            'model_id' => 'model',
            'car_type_id' => 'car type',
            'fuel_type_id' => 'fuel type',
            'city_id' => 'city'
        ];
    }
}
