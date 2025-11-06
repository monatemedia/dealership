<?php // app/Http/Requests/StoreVehicleRequest.php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use App\Services\VinValidatorService;

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
            'subcategory_id' => 'required|exists:subcategories,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'model_id' => 'required|exists:models,id',
            'year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'price' => 'required|integer|min:0',
            'vin' => [
                'required',
                'string',
                'size:17',
                'regex:/^[A-HJ-NPR-Z0-9]+$/i',
                function ($attribute, $value, $fail) {
                    $validator = app(VinValidatorService::class);
                    $result = $validator->validateWithMessage($value);

                    if (!$result['valid']) {
                        $fail($result['message']);
                    }
                },
            ],
            'mileage' => 'required|integer|min:0',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'transmission_id' => 'nullable|exists:transmissions,id',
            'drivetrain_id'  => 'nullable|exists:drivetrains,id',
            'color_id' => 'nullable|exists:colors,id',
            'interior_id' => 'nullable|exists:interiors,id',
            'accident_history_id' => 'nullable|exists:accident_histories,id',
            'service_history_id' => 'nullable|exists:service_histories,id',
            'exterior_condition_id' => 'nullable|exists:conditions,id',
            'interior_condition_id' => 'nullable|exists:conditions,id',
            'mechanical_condition_id' => 'nullable|exists:conditions,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
            'phone' => 'required|string|min:10',
            'description' => 'nullable|string',
            'published_at' => 'nullable|date',
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
            'subcategory_id.required' => 'Please select a sub-category',
            'vehicle_type_id.required' => 'Please select a vehicle type',
            'fuel_type_id.required' => 'Please select a fuel type',
            'fuel_type_id.exists' => 'The selected fuel type is invalid',
            'transmission_id.required' => 'Please select a transmission type',
            'transmission_id.exists' => 'The selected transmission type is invalid',
            'drivetrain_id.required' => 'Please select a drivetrain type',
            'drivetrain_id.exists' => 'The selected drivetrain type is invalid',
            'exterior_condition_id.exists' => 'The selected exterior condition is invalid',
            'interior_condition_id.exists' => 'The selected interior condition is invalid',
            'mechanical_condition_id.exists' => 'The selected mechanical condition is invalid',
            'service_history_id.exists' => 'The selected service history is invalid',
        ];
    }

    public function attributes()
    {
        return [
            'manufacturer_id' => 'manufacturer',
            'model_id' => 'model',
            'vehicle_type_id' => 'vehicle type',
            'fuel_type_id' => 'fuel type',
            'transmission_id' => 'transmission',
            'drivetrain_id' => 'drivetrain',
            'city_id' => 'city',
            'main_category_id' => 'main category',
            'subcategory_id' => 'sub-category',

        ];
    }
}
