<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
//    public function authorize(): bool
//    {
//        return auth()->check();
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:99999999.99',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Поле сумма обязательно для заполнения.',
            'amount.numeric' => 'Сумма должна быть числом.',
            'amount.min' => 'Сумма должна быть не менее 1 рубля.',
            'amount.max' => 'Сумма не может превышать 99,999,999.99 рублей.',
            'amount.decimal' => 'Сумма должна содержать не более 2 знаков после запятой.',
        ];
    }
}
