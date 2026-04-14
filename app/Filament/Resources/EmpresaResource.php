<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpresaResource\Pages;
use App\Models\Empresa;
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
use App\Models\Country;
use App\Models\Department;
use App\Models\City;
use UnitEnum;

class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static ?string $navigationLabel = 'Empresas';

    protected static string | UnitEnum | null $navigationGroup = 'Configurar';

    protected static ?string $label = 'Empresa';

    protected static ?string $pluralLabel = 'Empresas';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-s-building-office';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Forms\Components\TextInput::make('nit')
                    ->label('NIT')
                    ->required()
                    ->maxLength(20)
                    ->unique('empresa', 'nit', ignoreRecord: true),
                Forms\Components\TextInput::make('digito_verificacion')
                    ->label('Dígito de Verificación')
                    ->required()
                    ->maxLength(1),
                Forms\Components\TextInput::make('razon_social')
                    ->label('Razón Social')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nombre_comercial')
                    ->label('Nombre Comercial')
                    ->maxLength(255),
                Forms\Components\Textarea::make('direccion_fisica')
                    ->label('Dirección Física')
                    ->required()
                    ->maxLength(500)
                    ->rows(2),
                Forms\Components\Select::make('id_country')
                    ->label('País')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('id_department', null)),
                Forms\Components\Select::make('id_department')
                    ->label('Departamento')
                    ->options(function (callable $get) {
                        $countryId = $get('id_country');
                        if (!$countryId) {
                            return [];
                        }
                        return Department::where('id_country', $countryId)->pluck('name', 'id_department');
                    })
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('id_city', null)),
                Forms\Components\Select::make('id_city')
                    ->label('Ciudad')
                    ->options(function (callable $get) {
                        $departmentId = $get('id_department');
                        if (!$departmentId) {
                            return [];
                        }
                        return City::where('id_department', $departmentId)->pluck('name', 'id_city');
                    })
                    ->searchable(),
                Forms\Components\TextInput::make('telefono_contacto')
                    ->label('Teléfono de Contacto')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email_corporativo')
                    ->label('Email Corporativo')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email_facturacion')
                    ->label('Email de Facturación')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sitio_web')
                    ->label('Sitio Web')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('representante_legal')
                    ->label('Representante Legal')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cedula_representante')
                    ->label('Cédula del Representante')
                    ->maxLength(20),
                Forms\Components\FileUpload::make('logo_empresa')
                    ->label('Logo de la Empresa')
                    ->image()
                    ->directory('empresas/logos')
                    ->visibility('public')
                    ->columnSpan(2),
                Forms\Components\Select::make('regimen_fiscal')
                    ->label('Régimen Fiscal')
                    ->options([
                        'responsable_iva' => 'Responsable IVA',
                        'no_responsable' => 'No Responsable',
                        'simple' => 'Simple',
                    ])
                    ->required()
                    ->default('responsable_iva'),
                Forms\Components\TextInput::make('resolucion_dian')
                    ->label('Resolución DIAN')
                    ->maxLength(100),
                Forms\Components\TextInput::make('rango_inicio')
                    ->label('Rango Inicio')
                    ->numeric(),
                Forms\Components\TextInput::make('rango_fin')
                    ->label('Rango Fin')
                    ->numeric(),
                Forms\Components\Textarea::make('clave_tecnica')
                    ->label('Clave Técnica')
                    ->maxLength(500)
                    ->rows(2)
                    ->columnSpan(2),
                Forms\Components\Repeater::make('sucursales')
                    ->label('Sucursales')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('direccion')
                            ->label('Dirección')
                            ->required()
                            ->maxLength(500)
                            ->rows(2),
                        Forms\Components\TextInput::make('telefono')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'activa' => 'Activa',
                                'inactiva' => 'Inactiva',
                            ])
                            ->required()
                            ->default('activa'),
                        Forms\Components\Repeater::make('bodegas')
                            ->label('Bodegas')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('codigo')
                                    ->label('Código')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('responsable')
                                    ->label('Responsable')
                                    ->maxLength(255),
                                Forms\Components\Select::make('estado')
                                    ->label('Estado')
                                    ->options([
                                        'activa' => 'Activa',
                                        'inactiva' => 'Inactiva',
                                    ])
                                    ->required()
                                    ->default('activa'),
                            ])
                            ->columns(4)
                            ->minItems(1)
                            ->addActionLabel('Agregar Bodega')
                            ->deleteAction(fn ($action) => $action->requiresConfirmation())
                            ->collapsible(),
                    ])
                    ->columns(2)
                    ->minItems(1)
                    ->addActionLabel('Agregar Sucursal')
                    ->deleteAction(fn ($action) => $action->requiresConfirmation())
                    ->collapsible()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_empresa')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nit')
                    ->label('NIT')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('razon_social')
                    ->label('Razón Social')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_comercial')
                    ->label('Nombre Comercial')
                    ->searchable(),

                Tables\Columns\TextColumn::make('regimen_fiscal')
                    ->label('Régimen Fiscal')
                    ->badge()
                    ->colors([
                        'success' => 'responsable_iva',
                        'warning' => 'no_responsable',
                        'info' => 'simple',
                    ]),
                Tables\Columns\TextColumn::make('sucursales_count')
                    ->label('Sucursales')
                    ->counts('sucursales')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('regimen_fiscal')
                    ->label('Régimen Fiscal')
                    ->options([
                        'responsable_iva' => 'Responsable IVA',
                        'no_responsable' => 'No Responsable',
                        'simple' => 'Simple',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
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
                    ->label('Nueva Empresa')
                    ->visible(fn () => !Empresa::exists())
                    ->successNotificationTitle('Creado correctamente'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpresas::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}
