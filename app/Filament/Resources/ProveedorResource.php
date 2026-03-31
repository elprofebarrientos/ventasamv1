<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProveedorResource\Pages;
use App\Models\Proveedor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ProveedorResource extends Resource
{
    protected static ?string $model = Proveedor::class;

    protected static \BackedEnum | string | null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Proveedores';

    protected static ?string $modelLabel = 'Proveedor';

    protected static ?string $pluralModelLabel = 'Proveedores';

    protected static \UnitEnum | string | null $navigationGroup = 'Compras';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Forms\Components\Select::make('tipo_documento')
                    ->options([
                        'CC' => 'Cédula de Ciudadanía',
                        'NIT' => 'NIT',
                        'CE' => 'Cédula de Extranjería',
                        'PAP' => 'Pasaporte',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('numero_documento')
                    ->label('Número de Documento')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('digito_verificacion')
                    ->label('Dígito de Verificación')
                    ->numeric()
                    ->maxValue(9),
                Forms\Components\TextInput::make('razon_social')
                    ->label('Razón Social')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nombre_comercial')
                    ->label('Nombre Comercial')
                    ->maxLength(255),
                Forms\Components\TextInput::make('responsabilidad_fiscal')
                    ->label('Responsabilidad Fiscal')
                    ->maxLength(50),
                Forms\Components\Toggle::make('es_iva_responsable')
                    ->label('Es Responsable de IVA')
                    ->default(false),
                Forms\Components\Toggle::make('es_autoretenedor')
                    ->label('Es Autoretenedor')
                    ->default(false),
                Forms\Components\TextInput::make('correo_facturacion')
                    ->label('Correo de Facturación')
                    ->email()
                    ->maxLength(150),
                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(50),
                Forms\Components\Textarea::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255)
                    ->rows(2),
                Forms\Components\TextInput::make('codigo_postal')
                    ->label('Código Postal')
                    ->maxLength(10),
                Forms\Components\Select::make('id_pais')
                    ->label('País')
                    ->relationship('pais', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('id_departamento')
                    ->label('Departamento')
                    ->relationship('departamento', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('id_municipio')
                    ->label('Municipio')
                    ->relationship('municipio', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('plazo_pago_dias')
                    ->label('Plazo de Pago (días)')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('cupo_credito')
                    ->label('Cupo de Crédito')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
                Forms\Components\Toggle::make('estado')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo_documento')
                    ->label('Tipo Doc.')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'CC' => 'info',
                        'NIT' => 'success',
                        'CE' => 'warning',
                        'PAP' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('numero_documento')
                    ->label('Número Documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('digito_verificacion')
                    ->label('DV')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('razon_social')
                    ->label('Razón Social')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_comercial')
                    ->label('Nombre Comercial')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('correo_facturacion')
                    ->label('Correo')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('estado')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_documento')
                    ->options([
                        'CC' => 'Cédula de Ciudadanía',
                        'NIT' => 'NIT',
                        'CE' => 'Cédula de Extranjería',
                        'PAP' => 'Pasaporte',
                    ]),
                Tables\Filters\TernaryFilter::make('estado')
                    ->label('Activo'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProveedores::route('/'),
            'create' => Pages\CreateProveedor::route('/create'),
            'edit' => Pages\EditProveedor::route('/{record}/edit'),
        ];
    }
}
