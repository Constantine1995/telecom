<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Тариф';
    protected static ?string $navigationLabel = 'Тэги';
    protected static ?string $modelLabel = 'Тэг';
    protected static ?string $pluralModelLabel = 'Тэги';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Название')
                    ->required(),
                ColorPicker::make('bg-color')
                    ->label('Цвет фона')
                    ->default('#FFFFFF')
                    ->required(),
                ColorPicker::make('text-color')
                    ->label('Цвет текста')
                    ->default('#000000')
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
                TextColumn::make('bg-color')
                    ->label('Цвет фона')
                    ->alignCenter()
                    ->formatStateUsing(fn($record) => self::getColorFrom($record->{'bg-color'}))
                    ->html(),
                TextColumn::make('text-color')
                    ->label('Цвет текста')
                    ->alignCenter()
                    ->formatStateUsing(fn($record) => self::getColorFrom($record->{'text-color'}))
                    ->html(),
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }

    public static function getColorFrom(string $hex): string
    {
        return "<div style='width: 20px; height: 20px; background-color: $hex; border-radius: 4px;'></div>";
    }
}
