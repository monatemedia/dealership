<?php // app/Http/Requests/StoreVehicleRequest.php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreVehicleRequest extends FormRequest
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
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'model_id' => 'required|exists:models,id',
            'year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'price' => 'required|integer|min:0',
            'vin' => 'required|string|size:17',
            'mileage' => 'required|integer|min:0',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'transmission_id' => 'nullable|exists:transmissions,id',
            'drivetrain_id'  => 'nullable|exists:drivetrains,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
            'phone' => 'required|string|min:10',
            'description' => 'nullable|string',
            'published_at' => 'nullable|string',
            'features' => 'array',
            'features.*' => 'string',
            'ownership_paperwork' => 'array', // Add this
            'ownership_paperwork.*' => 'string', // Add this
            'images' => 'array',
            'images.*' => File::image()
                ->max(2048)
        ];
    }

    public function messages()
    {
        return [
            'required' => 'This field is required',
            'manufacturer_id.required' => 'Please select a manufacturer',
            'main_category_id.required' => 'Please select a main category',
            'sub_category_id.required' => 'Please select a sub-category',
            'vehicle_type_id.required' => 'Please select a vehicle type',
        ];
    }

    public function attributes()
    {
        return [
            'manufacturer_id' => 'manufacturer',
            'model_id' => 'model',
            'vehicle_type_id' => 'vehicle type',
            'fuel_type_id' => 'fuel type',
            'city_id' => 'city',
            'main_category_id' => 'main category',
            'sub_category_id' => 'sub-category',
        ];
    }
}
