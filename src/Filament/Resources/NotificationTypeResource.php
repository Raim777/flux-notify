<?php

namespace Raim\FluxNotify\Filament\Resources;

use Raim\FluxNotify\Filament\Resources\NotificationTypeResource\Pages;
use Raim\FluxNotify\Filament\Resources\NotificationTypeResource\RelationManagers;
use Raim\FluxNotify\Helpers\LangHelper;
use Raim\FluxNotify\Models\NotificationType;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotificationTypeResource extends Resource
{
    use Translatable;
    protected static ?string $model = NotificationType::class;

    protected static ?string $modelLabel = 'типы уведомлений';
    protected static ?string $pluralModelLabel = 'Типы уведомлений';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function getTranslatableLocales(): array
    {
        return LangHelper::LANGUAGES;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(trans('admin.name')),
                Forms\Components\TextInput::make('slug')
                    ->label(trans('admin.key')),
                Forms\Components\Toggle::make('is_active')
                    ->label(trans('admin.is_active')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('admin.name')),
                Tables\Columns\TextColumn::make('slug')
                    ->label(trans('admin.key')),
                Tables\Columns\BooleanColumn::make('is_active')->label(trans('admin.is_active')),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationTypes::route('/'),
            'create' => Pages\CreateNotificationType::route('/create'),
            'edit' => Pages\EditNotificationType::route('/{record}/edit'),
        ];
    }
}
