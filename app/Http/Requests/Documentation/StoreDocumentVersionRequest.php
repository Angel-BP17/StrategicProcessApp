<?php

namespace App\Http\Requests\Documentation;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $extensions = config('documentation.allowed_extensions', []);
        $fileRules = ['required', 'file', 'max:' . config('documentation.max_upload_size_kb', 25600)];

        if (!empty($extensions)) {
            $fileRules[] = 'mimes:' . implode(',', $extensions);
        }

        return [
            'file' => $fileRules,
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}