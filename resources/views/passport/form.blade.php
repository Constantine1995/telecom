<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Паспортные данные
        </h2>
    </header>
    <div class="mt-6 space-y-6 text-start">
        <div>
            <x-input-label for="last_name" value="Фамилия"/>
            <x-text-input id="last_name"
                          name="last_name"
                          value="{{ old('last_name', $passport->last_name ?? '') }}"
                          type="text"
                          class="mt-1 block w-full"
            />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
        </div>

        <div>
            <x-input-label for="first_name" value="Имя"/>
            <x-text-input id="first_name"
                          name="first_name"
                          value="{{ old('first_name', $passport->first_name ?? '') }}"
                          type="text"
                          class="mt-1 block w-full"
            />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
        </div>

        <div>
            <x-input-label for="middle_name" value="Отчество"/>
            <x-text-input id="middle_name"
                          name="middle_name"
                          value="{{ old('middle_name', $passport->middle_name ?? '') }}"
                          type="text"
                          class="mt-1 block w-full"
            />
            <x-input-error :messages="$errors->get('middle_name')" class="mt-2"/>
        </div>

        <x-dropdown-input name="gender_type" class="block w-full">
            @foreach(\App\Enums\Gender::cases() as $type)
                <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
        </x-dropdown-input>

        <div>
            <x-input-label for="birth_date" value="Дата рождения"/>
            <x-input-date id="birth_date"
                          name="birth_date"
                          value="{{ old('birth_date', isset($passport) && $passport->birth_date ? $passport->birth_date->format('Y-m-d') : '') }}"
            />
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2"/>
        </div>

        <div>
            <x-input-label for="issue_date" value="Дата выдачи"/>
            <x-input-date id="issue_date"
                          name="issue_date"
                          value="{{ old('issue_date', isset($passport) && $passport->issue_date ? $passport->issue_date->format('Y-m-d') : '') }}"
            />
            <x-input-error :messages="$errors->get('issue_date')" class="mt-2"/>
        </div>

        <div>
            <x-input-label for="issue_by_organization" value="Кем выдано"/>
            <x-text-input id="issue_by_organization" name="issue_by_organization"
                          value="{{ old('issue_by_organization', $passport->issue_by_organization ?? '') }}"
                          type="text"
                          class="mt-1 block w-full"
            />
            <x-input-error :messages="$errors->get('issue_by_organization')" class="mt-2"/>
        </div>

        <div>
            <x-input-label for="issue_by_number" value="Код подразделения"/>
            <x-text-input id="issue_by_number"
                          name="issue_by_number"
                          value="{{ old('issue_by_number', $passport->issue_by_number ?? '') }}"
                          type="text"
                          class="mt-1 block w-full"
            />
            <x-input-error :messages="$errors->get('issue_by_number')" class="mt-2"/>
        </div>

        <div>
            <x-input-label for="birthplace" value="Место рождения"/>
            <x-text-input id="birthplace"
                          name="birthplace"
                          value="{{ old('birthplace', $passport->birthplace ?? '') }}"
                          type="text"
                          class="mt-1 block w-full"
            />
            <x-input-error :messages="$errors->get('birthplace')" class="mt-2"/>
        </div>

        <div>
            <x-input-label for="serial_number" value="Серия и номер"/>
            <x-text-input id="serial_number"
                          name="serial_number"
                          value="{{ old('serial_number', $passport->serial_number ?? '') }}"
                          type="text"
                          class="mt-1 block w-full"
            />
            <x-input-error :messages="$errors->get('serial_number')" class="mt-2"/>
        </div>

        <div class="flex flex-col gap-2 border-b-2">
            <label>Фото главного разворота паспорта</label>
            <x-upload-file
                    wire:model.live="main_photo"
                    id="main_photo"
                    name="main_photo"
                    current="{{ isset($passport, $passport->main_photo) && $passport->main_photo ? asset('storage/' . $passport->main_photo) : '' }}"
            >
            </x-upload-file>
            <x-input-error :messages="$errors->get('main_photo')" class="mt-2"/>
        </div>

        <div class="flex flex-col gap-2 border-b-2">
            <label>Фото страницы с регистрацией</label>
            <x-upload-file
                    wire:model.live="registration_photo"
                    id="registration_photo"
                    name="registration_photo"
                    current="{{ isset($passport, $passport->registration_photo) && $passport->registration_photo ? asset('storage/' . $passport->registration_photo) : '' }}"
            >
            </x-upload-file>
            <x-input-error :messages="$errors->get('registration_photo')" class="mt-2"/>
        </div>

        <h2 class="text-lg font-medium text-gray-900 text-center ">
            Место жительства
        </h2>

        {{--        Место жительства        --}}
        <div>
            <x-input-label for="registration_date" value="Регистрация места жительства"/>
            <x-input-date id="registration_date"
                          name="registration_date"
                          value="{{ old('registration_date', isset($passport) && $passport->registration_date ? $passport->registration_date->format('Y-m-d') : '') }}"
            />
            <x-input-error :messages="$errors->get('registration_date')" class="mt-2"/>
        </div>

        <livewire:address-selector :passport="$passport"/>

        <div>
            <x-input-label for="street" value="Улица"/>
            <x-text-input id="street"
                          name="street"
                          wire:model.live="street"
                          value="{{ old('street', $passport->address->street ?? '') }}"
                          type="text"
                          class="mt-1 block w-full"
            />
            <x-input-error :messages="$errors->get('street')" class="mt-2"/>
        </div>
    </div>
</section>
