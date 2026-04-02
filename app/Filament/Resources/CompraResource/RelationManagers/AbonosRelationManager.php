<?php

namespace App\Filament\Resources\CompraResource\RelationManagers;

use App\Models\Abono;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class AbonosRelationManager extends RelationManager
{
    protected static string $relationship = 'abonos';

    protected static ?string $title = 'Abonos';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        $compra = $this->ownerRecord;
        $totalNetoPagar = $compra->total_neto_pagar;
        $montoPagado = $compra->abonos()->sum('monto');
        $saldoPendiente = $totalNetoPagar - $montoPagado;

        return $schema
            ->components([
                Forms\Components\Placeholder::make('info_compra')
                    ->label('Información de la Compra')
                    ->content(function () use ($totalNetoPagar, $saldoPendiente, $montoPagado) {
                        $info = "Total Neto a Pagar: $" . number_format($totalNetoPagar, 2, ',', '.');
                        $info .= "\nSaldo Pendiente: $" . number_format($saldoPendiente, 2, ',', '.');
                        if ($montoPagado > 0) {
                            $info .= "\nMonto Pagado: $" . number_format($montoPagado, 2, ',', '.');
                        }
                        return $info;
                    }),

                Forms\Components\TextInput::make('monto')
                    ->label('Monto')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0.01)
                    ->maxValue(fn () => $saldoPendiente)
                    ->rules([
                        function () use ($saldoPendiente) {
                            return function (string $attribute, $value, \Closure $fail) use ($saldoPendiente) {
                                if ($value > $saldoPendiente) {
                                    $fail('El monto no puede ser mayor al saldo pendiente de $' . number_format($saldoPendiente, 2, ',', '.'));
                                }
                            };
                        },
                    ]),

                Forms\Components\TextInput::make('monto_restante')
                    ->label('Monto Restante')
                    ->numeric()
                    ->prefix('$')
                    ->default(fn () => $saldoPendiente)
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\Select::make('metodo_pago')
                    ->label('Método de Pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'cheque' => 'Cheque',
                        'tarjeta_credito' => 'Tarjeta de Crédito',
                        'tarjeta_debito' => 'Tarjeta de Débito',
                        'otro' => 'Otro',
                    ])
                    ->required()
                    ->default('efectivo'),

                Forms\Components\Textarea::make('nota')
                    ->label('Nota')
                    ->maxLength(65535)
                    ->rows(3),

                Forms\Components\FileUpload::make('evidencia')
                    ->label('Evidencia de Pago')
                    ->directory('abonos/evidencias')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->maxSize(5120),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto')
                    ->money('COP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('monto_restante')
                    ->label('Monto Restante')
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $compra = $record->compra;
                        $totalNetoPagar = $compra->total_neto_pagar;
                        $montoPagado = $compra->abonos()->where('id', '<=', $record->id)->sum('monto');
                        $saldoPendiente = $totalNetoPagar - $montoPagado;
                        return '$' . number_format($saldoPendiente, 2, ',', '.');
                    }),

                Tables\Columns\TextColumn::make('metodo_pago')
                    ->label('Método de Pago')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'efectivo' => 'success',
                        'transferencia' => 'info',
                        'cheque' => 'warning',
                        'tarjeta_credito' => 'primary',
                        'tarjeta_debito' => 'primary',
                        'otro' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'cheque' => 'Cheque',
                        'tarjeta_credito' => 'Tarjeta de Crédito',
                        'tarjeta_debito' => 'Tarjeta de Débito',
                        'otro' => 'Otro',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('nota')
                    ->label('Nota')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('evidencia')
                    ->label('Evidencia')
                    ->formatStateUsing(fn ($state) => $state ? 'Ver archivo' : 'Sin archivo')
                    ->url(fn ($state) => $state ? asset('storage/' . $state) : null, true)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('metodo_pago')
                    ->label('Método de Pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'cheque' => 'Cheque',
                        'tarjeta_credito' => 'Tarjeta de Crédito',
                        'tarjeta_debito' => 'Tarjeta de Débito',
                        'otro' => 'Otro',
                    ]),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Agregar Abono')
                    ->after(function ($record) {
                        $record->compra->actualizarEstado();
                    }),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->after(function ($record) {
                        $record->compra->actualizarEstado();
                    }),
                Actions\DeleteAction::make()
                    ->after(function ($record) {
                        $record->compra->actualizarEstado();
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
