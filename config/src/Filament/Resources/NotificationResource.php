<?php

namespace Raim\FluxNotify\Filament\Resources;

use Raim\FluxNotify\Filament\Resources\NotificationResource\Pages;
use Raim\FluxNotify\Models\Notification;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $modelLabel = 'уведомления';
    protected static ?string $pluralModelLabel = 'Уведомления';
    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label(trans('admin.key')),

                Forms\Components\TextInput::make('description')
                    ->label(trans('admin.description')),
                Forms\Components\TextInput::make('subject')
                    ->label(trans('admin.subject')),
                Forms\Components\TextInput::make('text')
                    ->label(trans('admin.text')),
                Forms\Components\Select::make('notification_type_id')
                    ->relationship('notificationType', 'name')
                    ->preload()
                    ->label(trans('admin.notification_type')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label(trans('admin.subject')),

                Tables\Columns\TextColumn::make('text')
                    ->label(trans('admin.text')),
                Tables\Columns\TextColumn::make('description')
                    ->label(trans('admin.description')),
                Tables\Columns\TextColumn::make('notificationType.name')
                    ->sortable()
                    ->searchable()
                    ->label(trans('admin.notification_type')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
