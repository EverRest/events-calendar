<?php
declare(strict_types=1);

namespace App\Http\Requests\Event;

use App\Rules\NoOverlappingRecurringDates;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:3', 'max:255', 'string'],
            'description' => ['required', 'min:3', 'max:255', 'string'],
            'start' => ['required', 'date:Y-m-d H:i:s', new NoOverlappingRecurringDates(),],
            'end' => ['required', 'date:Y-m-d H:i:s', 'after:start'],
            'repeat_until' => ['required_with:frequency', 'date', 'after:end'],
            'frequency' => ['required_with:repeat_until', 'string', 'in:daily,weekly,monthly,yearly'],
        ];
    }
}
