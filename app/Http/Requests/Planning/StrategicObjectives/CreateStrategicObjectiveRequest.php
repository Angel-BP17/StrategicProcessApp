<?php

namespace App\Http\Requests\Planning\StrategicObjectives;

use Illuminate\Foundation\Http\FormRequest;

class CreateStrategicObjectiveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('plan.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plan_id' => ['required', 'exists:strategic_plans,id'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'goal_value' => ['nullable', 'numeric'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'responsible_user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
