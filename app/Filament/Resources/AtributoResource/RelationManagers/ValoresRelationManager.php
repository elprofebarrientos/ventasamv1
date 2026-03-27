<?php

namespace App\Filament\Resources\AtributoResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class ValoresRelationManager extends RelationManager
{
    protected static string $relationship = 'valores';

    protected static ?string $title = 'Valores';

    protected static ?string $recordTitleAttribute = 'valor';

    public function form(Schema $schema): Schema
    {
        $tipoVisual = $this->ownerRecord->tipo_visual;

        return $schema
            ->components([
                Forms\Components\Hidden::make('id_atributo')
                    ->default(fn () => $this->ownerRecord->id_atributo),
                Forms\Components\TextInput::make('valor')
                    ->label('Valor')
                    ->required()
                    ->maxLength(100),
                Forms\Components\ColorPicker::make('codigo_hex')
                    ->label('Código Hex')
                    ->visible($tipoVisual === 'COLOR')
                    ->required($tipoVisual === 'COLOR'),
                Forms\Components\FileUpload::make('imagen_url')
                    ->label('Imagen')
                    ->image()
                    ->directory('atributos')
                    ->visibility('public')
                    ->visible($tipoVisual === 'IMAGEN')
                    ->required($tipoVisual === 'IMAGEN'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_valor')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('valor')
                    ->label('Valor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('codigo_hex')
                    ->label('Código Hex'),
                Tables\Columns\ImageColumn::make('imagen_url')
                    ->label('Imagen'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Nuevo Valor'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
                Actions\ForceDeleteAction::make(),
                Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                    Actions\ForceDeleteBulkAction::make(),
                    Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
