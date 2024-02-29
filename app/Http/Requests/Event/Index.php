<?php
declare(strict_types=1);

namespace App\Http\Requests\Event;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Index extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['sometimes','min:3', 'max:255','string',],
            'page' => ['sometimes','integer', 'min:1'],
            'limit' => ['sometimes','integer', 'min:1'],
            'sort' => ['sometimes','string', 'in:id,title,description,start,end'],
            'order' => ['sometimes','string', 'in:asc,desc'],
        ];
    }
}
