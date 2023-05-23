<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCalendarRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'company' => ['required', 'string', 'exists:companies,code'],
            'postal_code' => ['required', 'string', 'regex:/[0-9]{4} ?[A-Z]{2}/'],
            'house_number' => ['required', 'string'],
            'remind_me_on' => ['required', Rule::in(['before', 'same'])],
            'remind_me_at' => ['required', 'date_format:H:i'],
            'pushover_key' => ['nullable', 'regex:/[A-Za-z0-9]{30}/'],
        ];
    }
}
