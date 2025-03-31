<?php

namespace App\Http\Controllers;

use App\Enums\PropertyType;
use App\Http\Requests\Passport\StorePassportRequest;
use App\Http\Requests\Passport\UpdatePassportRequest;
use App\Models\Address;
use App\Models\Passport;
use Illuminate\Support\Facades\Storage;

class PassportController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        // Редактируем если есть пассопрт
        if (auth()->user()->passport) {
            return redirect()->route('passport.edit', auth()->user()->passport);
        }
        // Иначе создаем
        $passport = new Passport();
        return view('passport.create', compact('passport'));
    }

    public function store(StorePassportRequest $request)
    {
        $validatedData = $request->validated();

        $address = Address::create([
            'region_id' => $validatedData['region_id'],
            'city_id' => $validatedData['city_id'],
            'property_type' => $validatedData['property_type'],
            'street' => $validatedData['street'],
            'house_number' => $validatedData['house_number'],
            'apartment_number' => $validatedData['apartment_number'] ?? null,
        ]);

        // Удаляем поля адреса из $validatedData
        $passportData = array_diff_key($validatedData, array_flip([
            'region_id',
            'city_id',
            'property_type',
            'street',
            'house_number',
            'apartment_number',
        ]));

        $passportData['user_id'] = auth()->id();
        $passportData['address_id'] = $address->id;
        $passportData['main_photo'] = $request->file('main_photo')?->store('uploads/passports', 'public');
        $passportData['registration_photo'] = $request->file('registration_photo')?->store('uploads/passports', 'public');

        $passport = Passport::create($passportData);

        return redirect()
            ->route('passport.edit', $passport)
            ->with('status', 'passport-updated');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Passport $passport)
    {
        return view('passport.edit', compact('passport'));
    }

    public function update(UpdatePassportRequest $request, Passport $passport)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('main_photo')) {
            // Удалить старое фото, если оно есть
            if ($passport->main_photo) {
                Storage::disk('public')->delete($passport->main_photo);
            }
            // Добавить новые фото, если они были переданы
            $validatedData['main_photo'] = $request->file('main_photo')?->store('uploads/passports', 'public');
        } else {
            // Если фото не переданы, оставляем старые фото без изменений
            $validatedData['main_photo'] = $passport->main_photo;
        }

        if ($request->hasFile('registration_photo')) {
            // Удалить старое фото, если оно есть
            if ($passport->registration_photo) {
                Storage::disk('public')->delete($passport->registration_photo);
            }
            // Добавить новые фото, если они были переданы
            $validatedData['registration_photo'] = $request->file('registration_photo')?->store('uploads/passports', 'public');
        } else {
            // Если фото не переданы, оставляем старые фото без изменений
            $validatedData['registration_photo'] = $passport->registration_photo;
        }

        // Очищаем поле apartment_number если указан property_type = House
        if ($validatedData['property_type'] === PropertyType::HOUSE->value) {
            $validatedData['apartment_number'] = null;
        }

        // Обновляем данные адреса
        $addressData = [
            'region_id' => $validatedData['region_id'],
            'city_id' => $validatedData['city_id'],
            'property_type' => $validatedData['property_type'],
            'street' => $validatedData['street'],
            'house_number' => $validatedData['house_number'],
            'apartment_number' => $validatedData['property_type'] === PropertyType::HOUSE->value ? null : $validatedData['apartment_number'],
        ];

        // Обновляем адрес
        $passport->address->update($addressData);

        // Удаляем поля адреса из $validatedData
        $passportData = array_diff_key($validatedData, array_flip([
            'region_id',
            'city_id',
            'property_type',
            'street',
            'house_number',
            'apartment_number',
        ]));

        // Обновляем данные паспорта
        $passport->update($passportData);

        return redirect()
            ->route('passport.edit', $passport)
            ->with('status', 'passport-updated');
    }

    public function destroy(string $id)
    {
        //
    }
}
