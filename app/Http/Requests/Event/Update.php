<?php
declare(strict_types=1);

namespace App\Http\Requests\Event;

use App\Rules\NoOverlappingRecurringDates;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $event = request()
            ->route('event');

        return [
            'title' => ['sometimes', 'min:3', 'max:255', 'string'],
            'description' => ['sometimes', 'min:3', 'max:255', 'string'],
            'start' => [
                'sometimes',
                'date',
                new NoOverlappingRecurringDates($event->id),
            ],
            'end' => ['sometimes', 'date', 'after:start'],
            'repeat_until' => ['sometimes', 'date', 'after:end'],
            'frequency' => ['sometimes', 'string', 'in:daily,weekly,monthly,yearly'],
        ];
    }
}
