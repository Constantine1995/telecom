<?php

namespace App\Filament\Resources;

use App\Enums\ContractStatus;
use App\Enums\DeviceStatusType;
use App\Enums\PropertyType;
use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Helpers\TransactionHelper;
use App\Models\City;
use App\Models\Contract;
use App\Models\Device;
use App\Models\DeviceStatus;
use App\Models\Region;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Договоры';
    protected static ?string $navigationLabel = 'Договоры';
    protected static ?string $modelLabel = 'Договор';
    protected static ?string $pluralModelLabel = 'Договоры';

    public static function form(Form $form): Form
    {
        $isRequired = fn($get) => $get('contract_status') == ContractStatus::ACTIVE->value;

        return $form
            ->schema([
                Fieldset::make('Договор')
                    ->schema([
                        Select::make('user_id')
                            ->label('Клиент')
                            ->relationship('user', 'name')
                            ->required(),
                        Select::make('connect_request_id')
                            ->label('ID Заявления')
                            ->relationship('connectRequest', 'user_id')
                            ->required(),
                        Select::make('contract_status')
                            ->label('Статус договора')
                            ->options(collect(ContractStatus::cases())->mapWithKeys(function (ContractStatus $type) {
                                return [$type->value => $type->label()];
                            })->toArray())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => in_array($state, [ContractStatus::ACTIVE->value, ContractStatus::PENDING_ACTIVATION->value])
                                ? $set('date_disconnection', null)
                                : $set('date_disconnection', now()->toDateTimeString())
                            ),
                        Select::make('device_id')
                            ->label('Маршрутизатор')
                            ->relationship('device', 'name')
                            ->reactive()
                            ->searchable()
                            ->preload()
                            ->rules(fn($get) => $isRequired($get) ? ['required'] : [])
                            ->required(fn($get) => $isRequired($get))
                            ->suffixIcon(fn($get) => $isRequired($get) ? 'heroicon-o-star' : null)
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Название')
                                    ->required(),
                                TextInput::make('ip_address')
                                    ->label('IP-адрес')
                                    ->unique()
                                    ->required(),
                                TextInput::make('mac_address')
                                    ->label('MAC-адрес')
                                    ->mask('**:**:**:**:**:**')
                                    ->helperText('Введите MAC-адрес в формате XX:XX:XX:XX:XX:XX')
                                    ->rules(['required', 'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/'])
                                    ->unique()
                                    ->required(),
                                DatePicker::make('date_connection')
                                    ->label('Дата подключения')
                                    ->minDate(Carbon::create(2000, 1, 1))
                                    ->maxDate(now())
                                    ->required(),
                                Select::make('device_status')
                                    ->label('Статус устройства')
                                    ->options(
                                        collect(DeviceStatusType::cases())->mapWithKeys(function (DeviceStatusType $type) {
                                            return [$type->value => $type->label()];
                                        })->toArray()
                                    )
                                    ->required(),
                            ])
                            ->createOptionUsing(function ($data) {
                                return self::createDevice($data)->getKey();
                            }),
                        DateTimePicker::make('date_connection')
                            ->label('Дата подключения')
                            ->default(Carbon::now())
                            ->required(),
                        DateTimePicker::make('date_disconnection')
                            ->label('Дата отключения')
                            ->reactive()
                            ->nullable(),
                    ]),

                Fieldset::make('Информация о местоположении')->columns(1)
                    ->schema([
                        Select::make('address.region_id')
                            ->label('Регион')
                            ->options(Region::pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->native(false)
                            ->afterStateUpdated(function ($get, $set, $state) {
                                if ($state) {
                                    $set('address.city_id', null);
                                }
                            }),
                        Select::make('address.city_id')
                            ->label('Город')

                            ->options(function ($get) {
                                $regionId = $get('address.region_id');
                                if ($regionId) {
                                    return City::where('region_id', $regionId)->pluck('name', 'id');
                                }
                                return [];
                            })
                            ->hidden(fn($get) => !$get('address.region_id'))
                            ->required()
                            ->native(false),
                        TextInput::make('address.street')
                            ->label('Улица')
                            ->required(),
                        Select::make('address.property_type')
                            ->label('Тип жилья')
                            ->options(collect(PropertyType::cases())->mapWithKeys(function (PropertyType $type) {
                                return [$type->value => $type->label()];
                            })->toArray())
                            ->reactive()
                            ->default(PropertyType::APARTMENT->value)
                            ->afterStateUpdated(function ($get, $set, $state) {
                                if ($state === PropertyType::HOUSE->value) {
                                    $set('address.apartment_number', null);
                                }
                                $set('tariff_id', null);
                            }),
                        TextInput::make('address.house_number')
                            ->label('Номер дома')
                            ->required(),
                        TextInput::make('address.apartment_number')
                            ->label('Номер квартиры')
                            ->nullable()
                            ->hidden(fn($get) => $get('address.property_type') !== PropertyType::APARTMENT->value),
                    ]),
                Select::make('tariff_id')
                    ->label('Тариф')
                    ->reactive()
                    ->relationship('tariff', 'name', function ($query, $get) {
                        return $query->where('connection_type', $get('address.property_type'));
                    })
                    ->helperText(fn() => 'Выберите тариф, соответствующий выбранному типу дома.')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('ID клиента')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('contract_number')
                    ->label('Номер договора')
                    ->sortable(),
                TextColumn::make('connect_request_id')
                    ->label('ID Заявления')
                    ->sortable(),
                TextColumn::make('tariff.name')
                    ->label('Тариф')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('contract_status')
                    ->label('Статус договора')
                    ->formatStateUsing(fn($state) => $state->label())
                    ->sortable(),
                TextColumn::make('address.region.name')
                    ->label('Регион')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('address.city.name')
                    ->label('Город')
                    ->sortable(),
                TextColumn::make('address.property_type')
                    ->label('Тип жилья')
                    ->formatStateUsing(fn($state) => $state->label())
                    ->sortable(),
                TextColumn::make('address.street')
                    ->label('Улица')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('address.house_number')
                    ->label('Номер дома')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('address.apartment_number')
                    ->label('Номер квартиры')
                    ->getStateUsing(fn(Contract $record): string => $record?->address?->apartment_number ?? 'N/A')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('device.name')
                    ->label('Маршрутизатор')
                    ->getStateUsing(fn(Contract $record): string => $record?->device?->name ?? 'N/A')
                    ->sortable(),
                TextColumn::make('date_connection')
                    ->label('Дата подключения')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_disconnection')
                    ->label('Дата отключения')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }

    public static function createDevice(array $data): Device
    {
        return Device::create([
            'device_status_id' => $data['device_status_id'],
            'name' => $data['name'],
            'ip_address' => $data['ip_address'],
            'mac_address' => $data['mac_address'],
            'date_connection' => $data['date_connection'],
        ]);
    }
}
