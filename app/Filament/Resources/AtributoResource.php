<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AtributoResource\Pages;
use App\Filament\Resources\AtributoResource\RelationManagers;
use App\Models\Atributo;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AtributoResource extends Resource
{
    protected static ?string $model = Atributo::class;

    protected static ?string $navigationLabel = 'Atributos';

    protected static ?string $label = 'Atributo';

    protected static ?string $pluralLabel = 'Atributos';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-s-queue-list';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(100)
                    ->unique('atributos', 'nombre', ignoreRecord: true),
                Forms\Components\Select::make('tipo_visual')
                    ->label('Tipo Visual')
                    ->options([
                        'TEXTO' => 'Texto',
                        'COLOR' => 'Color',
                        'IMAGEN' => 'Imagen',
                    ])
                    ->required()
                    ->default('TEXTO'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_atributo')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_visual')
                    ->label('Tipo Visual')
                    ->badge()
                    ->colors([
                        'primary' => 'TEXTO',
                        'success' => 'COLOR',
                        'warning' => 'IMAGEN',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make()
                    ->label('Agregar Valores'),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modal()
                    ->label('Nuevo Atributo'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ValoresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAtributos::route('/'),
            'create' => Pages\CreateAtributo::route('/create'),
            'edit' => Pages\EditAtributo::route('/{record}/edit'),
        ];
    }
}