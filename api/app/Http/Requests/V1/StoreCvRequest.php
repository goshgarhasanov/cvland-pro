<?php

declare(strict_types=1);

namespace App\Http\Requests\V1;

use App\Domain\CV\Enums\CvStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:140'],
            'template_id' => ['nullable', 'integer', 'exists:templates,id'],
            'status' => ['sometimes', Rule::in(array_column(CvStatus::cases(), 'value'))],
            'data' => ['sometimes', 'array'],
            'is_public' => ['sometimes', 'boolean'],
        ];
    }
}
