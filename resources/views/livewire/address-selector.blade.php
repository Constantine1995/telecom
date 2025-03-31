<div class="mt-6 space-y-6 text-start">
    <!-- Выбор региона-->
    <div>
        <x-dropdown-input wire:model.live="region_id" name="region_id" class="block w-full">
            <option value="">Выберите регион</option>
            @foreach($this->regions as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </x-dropdown-input>
        <x-input-error :messages="$errors->get('region_id')" class="mt-2"/>
    </div>

    <!-- Выбор города -->
    <div>
        <x-dropdown-input wire:model.live="city_id" name="city_id" class="block w-full">
            <option value="">Выберите город</option>
            @foreach($this->cities as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </x-dropdown-input>
        <x-input-error :messages="$errors->get('city_id')"/>
    </div>

    <!-- Выбор типа недвижимости -->
    <x-dropdown-input wire:model.live="property_type" name="property_type" class="block w-full">
        @foreach(\App\Enums\PropertyType::cases() as $type)
            <option value="{{ $type->value }}">{{ $type->label() }}</option>
        @endforeach
    </x-dropdown-input>

    <!-- Выбор номер дома -->
    <div>
        <x-input-label for="house_number" value="Номер дома"/>
        <x-text-input
                wire:model.live="house_number"
                id="house_number"
                name="house_number"
                type="text"
                class="mt-1 block w-full"/>
        <x-input-error :messages="$errors->get('house_number')" class="mt-2"/>
    </div>

    @if($property_type === \App\Enums\PropertyType::APARTMENT->value)
        <div>
            <x-input-label for="apartment_number" value="Номер квартиры"/>
            <x-text-input wire:model.live="apartment_number"
                          id="apartment_number"
                          name="apartment_number"
                          type="text"
                          class="mt-1 block w-full"/>
            <x-input-error :messages="$errors->get('apartment_number')" class="mt-2"/>
        </div>
    @endif

</div>
