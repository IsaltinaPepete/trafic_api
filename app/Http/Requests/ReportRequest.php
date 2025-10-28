<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'nullable|email',
            'images' => 'nullable|array',
            'phone_number' => 'nullable|string',
            'incident_date' => 'required|date',
            'incident_type' => 'required|string',
            'incident_description' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
        ];
    }
}
