<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKpiMeasurementRequest extends FormRequest
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
            'kpi_id' => ['required', 'exists:kpis,id'],
            'measured_at' => ['required', 'date'],
            'value' => ['required', 'numeric'],
            'source' => ['nullable', 'string', 'max:255'],
        ];
    }
}
