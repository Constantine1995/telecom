<?php

namespace App\Filament\Resources;

use App\Enums\ConnectRequestStatus;
use App\Enums\PropertyType;
use App\Filament\Resources\ConnectRequestResource\Pages;
use App\Filament\Resources\ConnectRequestResource\RelationManagers;
use App\Models\City;
use App\Models\ConnectRequest;
use App\Models\Region;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConnectRequestResource extends Resource
{
    protected static ?string $model = ConnectRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Заявления на подключения';
    protected static ?string $navigationLabel = 'Заявления';
    protected static ?string $modelLabel = 'Заявление';
    protected static ?string $pluralModelLabel = 'Заявления';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('Warning')
                    ->label('Для создания заявления, требуется заполненный паспорт пользователя!'),
                Fieldset::make()->columns(1)
                    ->schema([
                Select::make('user_id')
                    ->label('Клиент')
                    ->relationship('user', 'name')
                    ->required()
                    ->rules([
                        function () {
                            return function (string $attribute, $value, $fail) {
                                $hasPassport = auth()->user()->hasPassport();
                                if (!$hasPassport) {
                                    $fail('У пользователя не заполнен паспорт. Пожалуйста, добавьте данные паспорта перед подачей заявления.');
                                }
                            };
                        },
                    ]),
                TextInput::make('name')
                    ->label('Как обращаться к клиенту (имя)')
                    ->maxLength(50)
                    ->required(),
                TextInput::make('phone')
                    ->label('Номер телефона')
                    ->required()
                    ->mask('+7 (999) 999 99 99')
                    ->placeholder('+7 (999) 999 99 99')
                    ->dehydrateStateUsing(function ($state) {
                        $cleanNumber = preg_replace('/[^\d]/', '', $state);
                        if (str_starts_with($cleanNumber, '8') && strlen($cleanNumber) === 11) {
                            return '7' . substr($cleanNumber, 1);
                        }
                        return substr($cleanNumber, 0, 11);
                    }),
                        Select::make('connect_request_status_type')
                            ->label('Статус заявления')
                            ->options(collect(ConnectRequestStatus::cases())->mapWithKeys(function (ConnectRequestStatus $type) {
                                return [$type->value => $type->label()];
                            })->toArray())
                            ->required()
                            ->native(false)
                    ]),

                Fieldset::make('Адрес для подключения')->columns(1)
                    ->schema([
                        Select::make('address.region_id')
                            ->label('Регион')
                            ->options(Region::pluck('name', 'id'))
                            ->required()
                            ->reactive()
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
                            ->required(),
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
                                    $set('address.apartment_number', null); // Убрать поле apartment_number, если выбран дом
                                }
                            }),
                        TextInput::make('address.house_number')
                            ->label('Номер дома')
                            ->required(),
                        TextInput::make('address.apartment_number')
                            ->label('Номер квартиры')
                            ->nullable()
                            ->hidden(fn($get) => $get('address.property_type') !== PropertyType::APARTMENT->value),
                    ]),
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
                TextColumn::make('connect_request_status_type')
                    ->label('Статус заявления')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Имя клиента')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->numeric()
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
                    ->formatStateUsing(fn ($state) => $state->label())
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
                    ->getStateUsing(fn(ConnectRequest $record): string => $record?->address?->apartment_number ?? 'N/A')
                    ->numeric()
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
                //
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
            'index' => Pages\ListConnectRequests::route('/'),
            'create' => Pages\CreateConnectRequest::route('/create'),
            'edit' => Pages\EditConnectRequest::route('/{record}/edit'),
        ];
    }
}
