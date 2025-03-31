<?php

namespace App\Filament\Resources;

use App\Enums\Gender;
use App\Enums\PropertyType;
use App\Filament\Resources\UserResource\Pages;
use App\Models\City;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel= 'Клиенты';
    protected static ?string $modelLabel= 'Клиента';
    protected static ?string $pluralModelLabel= 'Клиенты';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Информация о клиенте')
                    ->schema([
                        TextInput::make('name')
                            ->label('Имя')
                            ->required(),
                        TextInput::make('email')
                            ->label('E-mail почта')
                            ->email()
                            ->unique('users', 'email', ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Этот email уже зарегистрирован.',
                            ])
                            ->required(),
                        TextInput::make('password')
                            ->label('Пароль')
                            ->password()
                            ->required(fn($livewire) => $livewire instanceof Pages\CreateUser)
                            ->visible(fn($livewire) => $livewire instanceof Pages\CreateUser)
                            ->rule('min:8')
                            ->hint('Пароль должен содержать 8 символов.')
                            ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                            ->nullable()
                            ->validationMessages([
                                'min' => 'Пароль должен содержать не менее 8 символов.',
                            ]),
                        DateTimePicker::make('email_verified_at')
                            ->label('E-mail подтвержден'),
                    ]),
                Section::make('Паспортные данные')
                    ->schema([
                        TextInput::make('passport.last_name')
                            ->label('Фамилия')
                            ->maxLength(50)
                            ->required(),
                        TextInput::make('passport.first_name')
                            ->label('Имя')
                            ->maxLength(50)
                            ->required(),
                        TextInput::make('passport.middle_name')
                            ->label('Отчество')
                            ->maxLength(50)
                            ->required(),
                        Select::make('passport.gender_type')
                            ->options(collect(Gender::cases())->mapWithKeys(function (Gender $type) {
                                return [$type->value => $type->label()];
                            })->toArray())
                            ->label('Пол')
                            ->required(),
                        DatePicker::make('passport.birth_date')
                            ->label('Дата рождения')
                            ->required()
                            ->minDate(Carbon::create(1900, 1, 1))
                            ->maxDate(now()->subYears(18)),
                        DatePicker::make('passport.issue_date')
                            ->label('Дата выдачи')
                            ->minDate(Carbon::create(1900, 1, 1))
                            ->maxDate(now())
                            ->required(),
                        TextInput::make('passport.issue_by_organization')
                            ->label('Кем выдано')
                            ->maxLength(100)
                            ->required(),
                        TextInput::make('passport.issue_by_number')
                            ->label('Код подразделения')
                            ->maxLength(15)
                            ->required(),
                        TextInput::make('passport.birthplace')
                            ->label('Место рождения')
                            ->maxLength(100)
                            ->required(),
                        TextInput::make('passport.serial_number')
                            ->label('Серия и номер')
                            ->length(10)
                            ->required(),
                        FileUpload::make('passport.main_photo')
                            ->label('Фото главного разворота паспорта')
                            ->required()
                            ->image()
                            ->directory('uploads/passports')
                            ->getUploadedFileNameForStorageUsing(fn($file) => $file->hashName())
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->maxSize(8192)
                            ->rules([
                                'required',
                                'image',
                                'mimes:jpeg,png',
                                'max:8192',
                            ])
                            ->validationMessages([
                                'required' => 'Поле обязательно для заполнения',
                                'image' => 'Файл должен быть изображением',
                                'uploaded' => 'Ошибка загрузки файла. Возможно, файл слишком большой.',
                            ])
                            ->hint('Допустимые форматы: JPEG, PNG. Максимальный размер: 8MB'),
                        FileUpload::make('passport.registration_photo')
                            ->label('Фото страницы с регистрацией')
                            ->required()
                            ->image()
                            ->directory('uploads/passports')
                            ->getUploadedFileNameForStorageUsing(fn($file) => $file->hashName())
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->maxSize(8192)
                            ->rules([
                                'required',
                                'image',
                                'mimes:jpeg,png',
                                'max:8192',
                            ])
                            ->validationMessages([
                                'required' => 'Поле обязательно для заполнения',
                                'image' => 'Файл должен быть изображением',
                                'uploaded' => 'Ошибка загрузки файла. Возможно, файл слишком большой.',
                            ])
                            ->hint('Допустимые форматы: JPEG, PNG. Максимальный размер: 8MB'),
                    ]),
                Section::make('Место жительства')
                    ->schema([
                        DatePicker::make('passport.registration_date')
                            ->label('Регистрация места жительства')
                            ->minDate(Carbon::create(1900, 1, 1))
                            ->maxDate(now())
                            ->required(),

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
                            ->maxLength(100)
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
                            ->maxLength(10)
                            ->required(),
                        TextInput::make('address.apartment_number')
                            ->label('Номер квартиры')
                            ->maxLength(10)
                            ->nullable()
                            ->hidden(fn($get) => $get('address.property_type') !== PropertyType::APARTMENT->value),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('passport.serial_number')
                    ->label('Серия и номер паспорта')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-mail почта')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
