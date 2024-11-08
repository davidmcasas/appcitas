<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'id_card' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'appointment_type' => ['required', Rule::in('first', 'revision')],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nombre',
            'id_card' => 'DNI',
            'phone' =>  'Teléfono',
            'email' => 'Email',
            'appointment_type' =>  'Tipo de cita',
        ];
    }
}
