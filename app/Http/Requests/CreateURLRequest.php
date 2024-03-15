<?php

namespace App\Http\Requests;

use App\Rules\Divisible;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateURLRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company' => ['required', 'string', 'exists:companies,code'],
            'postal_code' => ['required', 'string', 'regex:/[0-9]{4} ?[A-Z]{2}/'],
            'house_number' => ['required', 'string'],
            'remind_me_on' => ['required', Rule::in(['before', 'same'])],
            'remind_me_at' => ['required', 'date_format:H:i'],
            'duration' => ['required', 'integer', 'min:10', 'max:240', Divisible::by(5)],
        ];
    }
}
