<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHorarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $horarioId = $this->route('horario')?->id ?? $this->route('id');

        return [
            'dia_semana' => ['required', 'integer', 'between:1,6'],
            'hora_inicio' => [
                'required',
                'date_format:H:i',
                Rule::unique('horarios', 'hora_inicio')
                    ->where(fn($q) => $q->where('dia_semana', $this->input('dia_semana')))
                    ->ignore($horarioId),
            ],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('activo')) {
            $this->merge(['activo' => filter_var($this->input('activo'), FILTER_VALIDATE_BOOLEAN)]);
        }
    }
}

