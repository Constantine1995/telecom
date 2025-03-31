<?php

namespace App\Http\Requests\Passport;

use App\Enums\Gender;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdatePassportRequest extends FormRequest
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
            'last_name' => ['required', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:50'],
            'middle_name' => ['required', 'string', 'max:50'],
            'gender_type' => ['required', new Enum(Gender::class)],
            'birth_date' => [
                'required',
                'date',
                'after_or_equal:1900-01-01',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d')
            ],
            'issue_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
            'issue_by_organization' => ['required', 'string', 'max:100'],
            'issue_by_number' => ['required', 'string', 'max:15'],
            'birthplace' => ['required', 'string', 'max:100'],
            'serial_number' => ['required', 'string', 'size:10'],
            'main_photo' => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
            'registration_photo' => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
            'registration_date' => ['required', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:today'],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'property_type' => ['required', new Enum(PropertyType::class)],
            'house_number' => ['required', 'string', 'max:10'],
            'apartment_number' => ['nullable', 'string', 'max:10'],
            'street' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Обязательное поле.',

            'last_name.string' => 'Фамилия должна быть строкой.',
            'last_name.max' => 'Фамилия не может превышать 50 символов.',

            'first_name.string' => 'Имя должно быть строкой.',
            'first_name.max' => 'Имя не может превышать 50 символов.',

            'middle_name.string' => 'Отчество должно быть строкой.',
            'middle_name.max' => 'Отчество не может превышать 50 символов.',

            'gender_type.enum' => 'Выберите корректный пол.',

            'birth_date.date' => 'Введите корректную дату.',
            'birth_date.after_or_equal' => 'Дата рождения не может быть раньше 1 января 1900 года.',
            'birth_date.before_or_equal' => 'Вам должно быть не менее 18 лет.',

            'issue_date.date' => 'Введите корректную дату.',
            'issue_date.after_or_equal' => 'Дата выдачи не может быть раньше 1 января 1900 года.',
            'issue_date.before_or_equal' => 'Дата выдачи не может быть позже текущей даты.',

            'issue_by_organization.required' => 'Организация, выдавшая документ, обязательна.',
            'issue_by_organization.string' => 'Организация должна быть строкой.',
            'issue_by_organization.max' => 'Название организации не может превышать 100 символов.',

            'issue_by_number.string' => 'Номер документа должен быть строкой.',
            'issue_by_number.max' => 'Номер документа не может превышать 15 символов.',

            'birthplace.string' => 'Место рождения должно быть строкой.',
            'birthplace.max' => 'Место рождения не может превышать 100 символов.',

            'serial_number.string' => 'Серийный номер должен быть строкой.',
            'serial_number.size' => 'Серийный номер должен быть длиной 10 символов.',

            'main_photo.image' => 'Фотография должна быть изображением.',
            'main_photo.mimes' => 'Фотография должна быть в формате jpeg или png.',
            'main_photo.max' => 'Размер фотографии не может превышать 2 МБ.',

            'registration_photo.image' => 'Фотография регистрации должна быть изображением.',
            'registration_photo.mimes' => 'Фотография регистрации должна быть в формате jpeg или png.',
            'registration_photo.max' => 'Размер фотографии регистрации не может превышать 2 МБ.',

            'registration_date.date' => 'Введите корректную дату.',
            'registration_date.after_or_equal' => 'Дата регистрации не может быть раньше 1 января 1900 года.',
            'registration_date.before_or_equal' => 'Дата регистрации не может быть позже текущей даты.',

            'property_type.enum' => 'Выберите корректный тип дома.',

            'house_number.string' => 'Номер дома должен быть строкой.',
            'house_number.max' => 'Номер дома не может превышать 10 символов.',

            'apartment_number.string' => 'Номер квартиры должен быть строкой.',
            'apartment_number.max' => 'Номер квартиры не может превышать 10 символов.',

            'street.string' => 'Улица должна быть строкой.',
            'street.max' => 'Улица не может превышать 100 символов.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('apartment_number', 'required', function ($input) {
            return $input->property_type === PropertyType::APARTMENT->value;
        });
    }
}
