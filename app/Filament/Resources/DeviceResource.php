<?php

namespace App\Filament\Resources;

use App\Enums\DeviceStatusType;
use App\Filament\Resources\DeviceResource\Pages;
use App\Filament\Resources\DeviceResource\RelationManagers;
use App\Models\Device;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-o-wifi';
    protected static ?string $navigationGroup = 'Устройства';
    protected static ?string $navigationLabel = 'Устройства';
    protected static ?string $modelLabel = 'Устройство';
    protected static ?string $pluralModelLabel = 'Устройства';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Название')
                    ->required(),
                TextInput::make('ip_address')
                    ->label('IP-адрес')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('mac_address')
                    ->label('MAC-адрес')
                    ->mask('**:**:**:**:**:**')
                    ->helperText('Введите MAC-адрес в формате XX:XX:XX:XX:XX:XX')
                    ->rules(['required', 'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/'])
                    ->unique(ignoreRecord: true)
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->label('IP-адрес')
                    ->searchable(),
                TextColumn::make('mac_address')
                    ->label('MAC-адрес')
                    ->searchable(),
                TextColumn::make('date_connection')
                    ->label('Дата подключения')
                    ->date()
                    ->sortable(),
                TextColumn::make('device_status')
                    ->label('Статус устройства')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
            'index' => Pages\ListDevices::route('/'),
            'create' => Pages\CreateDevice::route('/create'),
            'edit' => Pages\EditDevice::route('/{record}/edit'),
        ];
    }
}
