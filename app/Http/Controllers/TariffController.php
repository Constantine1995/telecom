<?php

namespace App\Http\Controllers;

use App\Enums\PropertyType;
use App\Models\Tariff;
use Illuminate\View\View;

class TariffController extends Controller
{
    public function __invoke(): View
    {
        $tariffs = Tariff::all();

        // Группируем тарифы по типу подключения (connection_type)
        $groupedTariffs = $tariffs->groupBy('connection_type');

        // Получаем активные тарифы для домов, фильтруя по свойству active
        // Если таких тарифов нет, возвращаем пустую коллекцию
        $tariffForHouse = $groupedTariffs->get(PropertyType::HOUSE->value, collect())->filter(fn($tariff) => $tariff->active);

        // Получаем активные тарифы для квартир, фильтруя по свойству active
        // Если таких тарифов нет, возвращаем пустую коллекцию
        $tariffForApartment = $groupedTariffs->get(PropertyType::APARTMENT->value, collect())->filter(fn($tariff) => $tariff->active);

        // Проверяем, есть ли у текущего пользователя паспорт
        // Возвращаем false, если пользователь не аутентифицирован
        $hasPassport = auth()->user()?->hasPassport() ?? false;

        return view('tariff.index', compact(
            [
                'tariffForHouse',
                'tariffForApartment',
                'hasPassport'
            ]));
    }
}
