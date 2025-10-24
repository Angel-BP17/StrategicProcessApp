<?php

namespace App\Http\Requests\Documentation;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'document_version_id' => ['required', 'exists:document_versions,id'],
            'initiative_id' => ['nullable', 'exists:initiatives,id'],
            'audit_id' => ['nullable', 'exists:audits,id'],
            'accreditation_id' => ['nullable', 'exists:accreditations,id'],
            'kpi_measurement_id' => ['nullable', 'exists:kpi_measurements,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $this->hasAnyTarget()) {
                $validator->errors()->add('target', 'Selecciona al menos una entidad estratégica para vincular la evidencia.');
            }
        });
    }

    protected function hasAnyTarget(): bool
    {
        return collect([
            $this->input('initiative_id'),
            $this->input('audit_id'),
            $this->input('accreditation_id'),
            $this->input('kpi_measurement_id'),
        ])->filter()->isNotEmpty();
    }
}