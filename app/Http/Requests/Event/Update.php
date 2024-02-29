<?php
declare(strict_types=1);

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'min:3', 'max:255', 'string'],
            'description' => ['sometimes', 'min:3', 'max:255', 'string'],
            'start' => ['sometimes', 'date'],
            'end' => ['sometimes', 'date', 'after:start'],
            'repeat_until' => ['required_with:frequency', 'date', 'after:end'],
            'frequency' => ['required_with:repeat_until', 'string', 'in:daily,weekly,monthly,yearly'],
        ];
    }
}
