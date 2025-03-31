@php use App\Enums\PropertyType; @endphp
<div x-data>

    @if($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50"
             x-data
             @click="if ($event.target === $el) $wire.closeRequestModal()">
            <div class="flex flex-col w-[370px] text-gray-900 p-5 rounded-lg shadow-lg bg-white"
                 style="height: auto; overflow-y: inherit;
                 @click.stop">

                <div class="flex flex-row justify-between w-full">
                    <h2 class="text-2xl font-bold">Заказать услугу</h2>
                    <button wire:click="closeRequestModal" class="bg-gray-400 text-white rounded px-3">
                        X
                    </button>
                </div>

                @if($tariff)
                    <p class="text-xl font-medium">Тариф "{{$tariff['name']}}"</p>
                @endif

                <div class="flex flex-col gap-4 mt-4">

                    <p class="font-bold">Информация о вас</p>

                    <div>
                        <x-text-input wire:model="name" id="name" name="name" type="text"
                                      placeholder="Как к вам обращаться?"
                                      class=" placeholder-gray-500 block w-full"
                                      required autofocus/>
                        <x-input-error :messages="$errors->get('name')"/>
                    </div>

                    <div>
                        <x-text-input wire:model="phone" id="phone" name="phone" type="text"
                                      placeholder="+7(999) 999-99-99"
                                      x-mask="+7(999) 999-99-99"
                                      class=" placeholder-gray-500 mt-1 block w-full"
                                      required autofocus/>
                        <x-input-error :messages="$errors->get('phone')"/>
                    </div>

                    <p class="font-bold">Адрес для подключения</p>
                    <div>
                        <x-dropdown-input wire:model.live="region" name="region" class="block w-full">
                            <option>Выберите регион</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </x-dropdown-input>
                        <x-input-error :messages="$errors->get('region')"/>
                    </div>

                    <div>
                        <x-dropdown-input wire:model="city" id="city" name="city" class="block w-full">
                            @if ($cities->count() == 0)
                                <option>Выберите город</option>
                            @endif
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </x-dropdown-input>
                        <x-input-error :messages="$errors->get('city')"/>
                    </div>

                    <div>
                        <x-text-input wire:model="street" id="street" name="street" type="text"
                                      placeholder="Улица"
                                      class=" placeholder-gray-500 mt-1 block w-full"
                                      required autofocus/>
                        <x-input-error :messages="$errors->get('street')"/>
                    </div>

                    <div>
                        <x-text-input wire:model="houseNumber" id="houseNumber" name="houseNumber" type="text"
                                      placeholder="Номер дома" class="placeholder-gray-500 block w-full"
                                      required autofocus/>
                        <x-input-error :messages="$errors->get('houseNumber')"/>
                    </div>

                    @if($tariff['connection_type']->value === PropertyType::APARTMENT->value)
                        <div>
                            <x-text-input wire:model="apartmentNumber" id="apartmentNumber" name="apartmentNumber"
                                          type="text"
                                          placeholder="Номер квартиры" class="placeholder-gray-500 block w-full"
                                          required autofocus/>
                            <x-input-error :messages="$errors->get('apartmentNumber')"/>
                        </div>
                    @endif

                    <x-primary-button wire:click="sendRequest"
                                      class="justify-center mt-4 px-4 py-2 bg-gray-600 text-white rounded">
                        Отправить заявку
                    </x-primary-button>

                </div>
            </div>
        </div>
    @endif

</div>
