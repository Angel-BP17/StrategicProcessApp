<?php

namespace App\Http\Requests\Planning\Kpis;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKpiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('objective.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'objective_id' => ['required', 'exists:strategic_objectives,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_value' => ['nullable', 'numeric'],
            'unit' => ['nullable', 'string', 'max:50'],
            'frequency' => ['nullable', 'string', 'max:50'],
        ];
    }
}
