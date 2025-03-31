<?php

namespace App\Filament\Resources;

use App\Enums\PropertyType;
use App\Filament\Resources\TariffResource\Pages;
use App\Filament\Resources\TariffResource\RelationManagers;
use App\Models\Tag;
use App\Models\Tariff;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TariffResource extends Resource
{
    protected static ?string $model = Tariff::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Тариф';
    protected static ?string $navigationLabel = 'Тарифы';
    protected static ?string $modelLabel = 'Тариф';
    protected static ?string $pluralModelLabel = 'Тарифы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->required(),
                        Select::make('tag_id')
                            ->label('Тэг')
                            ->options(Tag::pluck('name', 'id'))
                            ->required(),
                        Select::make('connection_type')
                            ->label('Тип подключения')
                            ->options(collect(PropertyType::cases())->mapWithKeys(function (PropertyType $type) {
                                return [$type->value => $type->label()];
                            })->toArray())
                            ->reactive()
                            ->default(PropertyType::APARTMENT->value),
                        TextInput::make('price')
                            ->label('Цена/мес')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('₽'),
                        TextInput::make('connection_price')
                            ->label('Цена подключения')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('₽'),
                        TextInput::make('speed')
                            ->label('Скорость')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('active')
                            ->label('Активность')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Цена')
                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', ' ') . ' ₽')
                    ->sortable(),
                TextColumn::make('speed')
                    ->label('Скорость')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('active')
                    ->label('Активность')
                    ->boolean(),
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
            'index' => Pages\ListTariffs::route('/'),
            'create' => Pages\CreateTariff::route('/create'),
            'edit' => Pages\EditTariff::route('/{record}/edit'),
        ];
    }
}
