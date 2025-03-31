<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Баннеры';
    protected static ?string $modelLabel = 'Баннер';
    protected static ?string $pluralModelLabel = 'Баннеры';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('order')
                    ->label('Порядок нумерации')
                    ->numeric()
                    ->required(),
                ColorPicker::make('bg-color')
                    ->label('Цвет фона')
                    ->default('#FFFFFF')
                    ->required(),
                ColorPicker::make('text-color')
                    ->label('Цвет текста')
                    ->default('#000000')
                    ->required(),
                FileUpload::make('icon')
                    ->label('Иконка')
                    ->directory('banners')
                    ->getUploadedFileNameForStorageUsing(fn($file) => $file->hashName())
                    ->required()
                    ->validationMessages([
                        'required' => 'Поле обязательно для заполнения',
                        'image' => 'Файл должен быть изображением',
                    ]),
                TextInput::make('title')
                    ->label('Название')
                    ->required(),
                Textarea::make('description')
                    ->label('Описание')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('Нумерация')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('icon')
                    ->label('Иконка')
                    ->formatStateUsing(fn($state) => "<img src='" . asset('storage/' . $state) . "' class='w-15 h-10 object-contain'>")
                    ->alignCenter()
                    ->html(),
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }

    public static function getColorFrom(string $hex): string
    {
        return "<div style='width: 20px; height: 20px; background-color: $hex; border-radius: 4px;'></div>";
    }
}
