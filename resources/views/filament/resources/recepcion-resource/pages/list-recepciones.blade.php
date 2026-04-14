@php
use App\Models\Compra;
$compras = \App\Models\Compra::with(['proveedor', 'detalles.variante.producto'])
    ->where('resultado_recepcion', '!=', 'Completa')
    ->orderBy('created_at', 'desc')
    ->get();
@endphp

<x-filament-panels::page>
    <div style="background-color: #f9fafb; min-height: 100vh; padding: 1.5rem;">
        <div style="max-width: 72rem; margin: 0 auto;">
            <div style="background-color: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h2 style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">Recepción de Compras</h2>
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #6b7280; margin-left: 1rem;">Administra la recepción de productos comprados</p>
                        </div>
                        <span style="background-color: #dbeafe; color: #1d4ed8; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;">
                            {{ $compras->count() }} pendientes
                        </span>
                    </div>
                </div>

                @if($compras->isEmpty())
                    <div style="padding: 2rem; text-align: center;">
                        <svg style="margin: 0 auto; height: 3rem; width: 3rem; color: #9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 style="margin-top: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #111827;">No hay achats pendientes</h3>
                        <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #6b7280;">Todas las compras han sido recepcionadas.</p>
                    </div>
                @else
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f3f4f6;">
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Proveedor</th>
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Factura</th>
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Fecha</th>
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Total</th>
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Estado</th>
                                <th style="padding: 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compras as $compra)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; font-weight: 600; color: #111827;">{{ $compra->proveedor->razon_social ?? 'N/A' }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; color: #374151;">{{ $compra->numero_factura ?? 'N/A' }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; color: #4b5563;">{{ $compra->fecha_emision->format('d/m/Y') }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; font-weight: 700; color: #111827;">${{ number_format($compra->total_neto_pagar, 2, ',', '.') }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    @php
                                        $statusConfig = match($compra->resultado_recepcion) {
                                            'Por recibir' => ['background-color: #fef3c7; color: #92400e;', '#facc15'],
                                            'Incompleta' => ['background-color: #fee2e2; color: #991b1b;', '#ef4444'],
                                            'Con daños' => ['background-color: #fee2e2; color: #991b1b;', '#ef4444'],
                                            'Mixta' => ['background-color: #dbeafe; color: #1e40af;', '#3b82f6'],
                                            default => ['background-color: #f3f4f6; color: #374151;', '#6b7280']
                                        };
                                    @endphp
                                    <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background-color: {{ $statusConfig[1] }};"></span>
                                        <span style="font-size: 0.75rem; font-weight: 500; padding: 0.25rem 0.5rem; border-radius: 9999px; {{ $statusConfig[0] }}">
                                            {{ $compra->resultado_recepcion }}
                                        </span>
                                    </span>
                                </td>
                                <td style="padding: 1.25rem; text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <button 
                                            type="button"
                                            onclick="verRecepciones({{ $compra->id_compra }}, '{{ addslashes($compra->proveedor->razon_social ?? 'N/A') }}', '{{ $compra->numero_factura ?? 'N/A' }}')"
                                            style="background-color: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer;">
                                            Ver
                                        </button>
                                        @if($compra->resultado_recepcion != 'Completa')
                                        <button 
                                            type="button"
                                            onclick="openRecepcionModal({{ $compra->id_compra }}, '{{ addslashes($compra->proveedor->razon_social ?? 'N/A') }}', '{{ $compra->numero_factura ?? 'N/A' }}')"
                                            style="background-color: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer;">
                                            Recepcionar
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <div id="recepcion-modal" style="display: none; position: fixed; inset: 0; z-index: 50; overflow-y: auto;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div style="display: flex; min-height: 100vh; align-items: center; justify-content: center; padding: 1rem;">
            <div style="position: fixed; inset: 0; background-color: rgba(17, 24, 39, 0.75);" onclick="closeRecepcionModal()"></div>
            <div style="position: relative; transform: translateY(0); overflow: hidden; border-radius: 0.75rem; background-color: white; text-align: left; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); width: 100%; max-width: 100rem;">
                <div style="background-color: white; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1rem;">
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="modal-title">
                                Recepcionar Compra
                            </h3>
                            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;" id="compra-info"></p>
                        </div>
                        <button type="button" onclick="closeRecepcionModal()" style="color: #9ca3af;">
                            <svg style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form id="recepcion-form" method="POST" style="margin-top: 1rem;">
                        @csrf
                        <input type="hidden" name="id_compra" id="id_compra">
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Sucursal</label>
                            <select name="id_sucursal" id="id_sucursal" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;" onchange="loadBodegasPorSucursal(this.value)" required>
                                <option value="">Seleccionar Sucursal...</option>
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Productos a Recepcionar</label>
                            <div style="overflow: hidden; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                                <table style="width: 100%; min-width: 100%;">
                                    <thead>
                                <tr style="background-color: #f9fafb;">
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Producto</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Atributos</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Cantidad</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Recibida</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Recibido Ant.</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Faltante</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Bodega</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Ubicación</th>
                                    <th id="recepcion-lote-header" style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; display: none;">Lote</th>
                                    <th id="recepcion-vencimiento-header" style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; display: none;">Vencimiento</th>
                                </tr>
                            </thead>
                                    <tbody id="detalles-body" style="background-color: white;">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Observación</label>
                            <textarea name="observacion" id="observacion" rows="2" placeholder="Observación opcional..." style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"></textarea>
                        </div>
                    </form>
                </div>
                <div style="background-color: #f9fafb; padding: 0.75rem; display: flex; flex-direction: row-reverse; padding: 0.75rem 1.5rem;">
                    <button type="button" onclick="submitRecepcion()" style="width: 100%; justify-content: center; margin-left: 0.75rem; padding: 0.5rem 1rem; background-color: #2563eb; color: white; border-radius: 0.5rem; font-weight: 500;">
                        Guardar Recepción
                    </button>
                    <button type="button" onclick="closeRecepcionModal()" style="margin-top: 0.75rem; width: 100%; justify-content: center; padding: 0.5rem 1rem; background-color: #d1d5db; color: #374151; border-radius: 0.5rem; font-weight: 500;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="ver-recepcion-modal" style="display: none; position: fixed; inset: 0; z-index: 50; overflow-y: auto;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div style="display: flex; min-height: 100vh; align-items: center; justify-content: center; padding: 1rem;">
            <div style="position: fixed; inset: 0; background-color: rgba(17, 24, 39, 0.75);" onclick="closeVerRecepcionModal()"></div>
            <div style="position: relative; transform: translateY(0); overflow: hidden; border-radius: 0.75rem; background-color: white; text-align: left; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); width: 100%; max-width: 72rem;">
                <div style="background-color: white; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1rem;">
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">
                                Recepciones Registradas
                            </h3>
                            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;" id="ver-compra-info"></p>
                        </div>
                        <button type="button" onclick="closeVerRecepcionModal()" style="color: #9ca3af;">
                            <svg style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div style="overflow: hidden; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                        <table style="width: 100%; min-width: 100%;">
                            <thead>
                                <tr style="background-color: #f9fafb;">
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Fecha</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Observación</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Productos</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="recepciones-body" style="background-color: white;">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="background-color: #f9fafb; padding: 0.75rem; display: flex; flex-direction: row-reverse; padding: 0.75rem 1.5rem;">
                    <button type="button" onclick="closeVerRecepcionModal()" style="width: 100%; justify-content: center; padding: 0.5rem 1rem; background-color: #6b7280; color: white; border-radius: 0.5rem; font-weight: 500;">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="detalle-recepcion-modal" style="display: none; position: fixed; inset: 0; z-index: 60; overflow-y: auto;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div style="display: flex; min-height: 100vh; align-items: center; justify-content: center; padding: 1rem;">
            <div style="position: fixed; inset: 0; background-color: rgba(17, 24, 39, 0.75);" onclick="closeDetalleRecepcionModal()"></div>
            <div style="position: relative; transform: translateY(0); overflow: hidden; border-radius: 0.75rem; background-color: white; text-align: left; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); width: 100%; max-width: 72rem;">
                <div style="background-color: white; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1rem;">
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">
                                Detalle de Recepción
                            </h3>
                        </div>
                        <button type="button" onclick="closeDetalleRecepcionModal()" style="color: #9ca3af;">
                            <svg style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div style="overflow: hidden; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                        <table style="width: 100%; min-width: 100%;">
                            <thead>
                                <tr style="background-color: #f9fafb;">
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Producto</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Atributos</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Cantidad</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Bodega</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Ubicación</th>
                                </tr>
                            </thead>
                            <tbody id="detalle-recepcion-body" style="background-color: white;">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="background-color: #f9fafb; padding: 0.75rem; display: flex; flex-direction: row-reverse; padding: 0.75rem 1.5rem;">
                    <button type="button" onclick="closeDetalleRecepcionModal()" style="width: 100%; justify-content: center; padding: 0.5rem 1rem; background-color: #6b7280; color: white; border-radius: 0.5rem; font-weight: 500;">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let detallesCompra = [];
        
        function openRecepcionModal(compraId, proveedor, numeroFactura) {
            document.getElementById('id_compra').value = compraId;
            document.getElementById('compra-info').textContent = 'Proveedor: ' + proveedor + ' - Factura: ' + numeroFactura;
            
            document.getElementById('id_sucursal').innerHTML = '<option value="">Seleccionar Sucursal...</option>';
            loadSucursales();
            
            fetch('/api/compras/' + compraId + '/detalles')
                .then(response => response.json())
                .then(data => {
                    detallesCompra = data;
                    renderDetalles(data);
                    document.getElementById('recepcion-modal').style.display = 'block';
                });
        }

        let sucursales = [];
        
        function loadSucursales() {
            fetch('/api/sucursales')
                .then(response => response.json())
                .then(data => {
                    sucursales = data;
                    updateSucursalSelect();
                });
        }
        
        function updateSucursalSelect() {
            const select = document.getElementById('id_sucursal');
            select.innerHTML = '<option value="">Seleccionar Sucursal...</option>';
            sucursales.forEach(function(sucursal) {
                select.innerHTML += '<option value="' + sucursal.id_sucursal + '">' + sucursal.nombre + '</option>';
            });
        }
        
        function loadBodegasPorSucursal(sucursalId) {
            updateBodegaSelects();
            
            if (!sucursalId) {
                return;
            }
            
            fetch('/api/sucursales/' + sucursalId + '/bodegas')
                .then(response => response.json())
                .then(data => {
                    bodegas = data;
                    updateBodegaSelects();
                });
        }
        
        function closeRecepcionModal() {
            document.getElementById('recepcion-modal').style.display = 'none';
            document.getElementById('recepcion-form').reset();
            document.getElementById('id_sucursal').innerHTML = '<option value="">Seleccionar Sucursal...</option>';
            detallesCompra = [];
        }
        
        function renderDetalles(detalles) {
            const tbody = document.getElementById('detalles-body');
            tbody.innerHTML = '';
            
            let tieneLote = false;
            let tieneVencimiento = false;
            
            detalles.forEach(function(detalle) {
                if (detalle.variante_tiene_lote) tieneLote = true;
                if (detalle.variante_tiene_fecha_vencimiento) tieneVencimiento = true;
            });
            
            var loteHeader = document.getElementById('recepcion-lote-header');
            var vencimientoHeader = document.getElementById('recepcion-vencimiento-header');
            
            if (loteHeader) loteHeader.style.display = tieneLote ? 'table-cell' : 'none';
            if (vencimientoHeader) vencimientoHeader.style.display = tieneVencimiento ? 'table-cell' : 'none';
            
            document.querySelectorAll('.recepcion-lote-cell').forEach(function(el) {
                el.style.display = tieneLote ? 'table-cell' : 'none';
            });
            document.querySelectorAll('.recepcion-vencimiento-cell').forEach(function(el) {
                el.style.display = tieneVencimiento ? 'table-cell' : 'none';
            });
            
detalles.forEach(function(detalle, index) {
                let atributosTexto = 'N/A';
                if (detalle.variante && detalle.variante.valores && detalle.variante.valores.length > 0) {
                    atributosTexto = detalle.variante.valores.map(function(v) {
                        return v.atributo.nombre + ': ' + v.valor;
                    }).join(' | ');
                }
                
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #e5e7eb';
                const cantidadComprada = parseFloat(detalle.cantidad);
                const cantidadRecibidaAnterior = parseFloat(detalle.cantidad_recibida) || 0;
                const cantidadPendiente = parseFloat(detalle.cantidad_pendiente);
                
                let valorInicial = cantidadPendiente > 0 ? cantidadPendiente : 0;
                
                var cells = [];
                cells.push('<td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">' + (detalle.variante && detalle.variante.producto ? detalle.variante.producto.nombre : 'N/A') + '</td>');
                cells.push('<td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">' + atributosTexto + '</td>');
                cells.push('<td style="padding: 0.75rem; font-size: 0.875rem; color: #111827; font-weight: 500;">' + cantidadComprada.toFixed(2) + '</td>');
                cells.push('<td style="padding: 0.75rem;"><input type="number" name="detalles[' + index + '][cantidad_recibida]" id="cantidad_recibida_' + index + '" value="' + valorInicial + '" min="0" max="' + cantidadPendiente + '" step="0.01" style="font-size: 0.875rem; width: 5rem; padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem;" onchange="calcularFaltante(' + index + ', ' + cantidadPendiente + ')" oninput="calcularFaltante(' + index + ', ' + cantidadPendiente + ')" required><input type="hidden" name="detalles[' + index + '][id_variante]" value="' + detalle.id_variante + '"><input type="hidden" name="detalles[' + index + '][cantidad_comprada]" value="' + cantidadComprada + '"><input type="hidden" name="detalles[' + index + '][id_detalle]" value="' + detalle.id_detalle + '"></td>');
                cells.push('<td style="padding: 0.75rem; font-size: 0.875rem; font-weight: 600; color: #059669;">' + cantidadRecibidaAnterior.toFixed(2) + '</td>');
                cells.push('<td style="padding: 0.75rem; font-size: 0.875rem; font-weight: 600;" id="faltante_' + index + '">' + cantidadPendiente.toFixed(2) + '</td>');
                cells.push('<td style="padding: 0.75rem;"><select name="detalles[' + index + '][id_bodega]" style="font-size: 0.875rem; padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem;" onchange="loadUbicaciones(this.value, ' + index + ')" required><option value="">Seleccionar...</option></select></td>');
                cells.push('<td style="padding: 0.75rem;"><select name="detalles[' + index + '][id_ubicacion]" style="font-size: 0.875rem; padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem;" required disabled><option value="">Seleccionar bodega...</option></select></td>');
                
                cells.push('<td class="recepcion-lote-cell" style="padding: 0.75rem; display: ' + (tieneLote ? 'table-cell' : 'none') + ';"><input type="text" name="detalles[' + index + '][lote]" placeholder="Lote" style="font-size: 0.875rem; width: 6rem; padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem;"></td>');
                
                cells.push('<td class="recepcion-vencimiento-cell" style="padding: 0.75rem; display: ' + (tieneVencimiento ? 'table-cell' : 'none') + ';"><input type="date" name="detalles[' + index + '][fecha_vencimiento]" style="font-size: 0.875rem; width: 8rem; padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 0.25rem;"></td>');
                
                row.innerHTML = cells.join('');
                tbody.appendChild(row);
            });
        }
        
        let bodegas = [];
        
        function loadBodegasPorSucursalDesdeDetalle() {
            const sucursalId = document.getElementById('id_sucursal').value;
            if (sucursalId) {
                fetch('/api/sucursales/' + sucursalId + '/bodegas')
                    .then(response => response.json())
                    .then(data => {
                        bodegas = data;
                        updateBodegaSelects();
                    });
            } else {
                updateBodegaSelects();
            }
        }
        
        function updateBodegaSelects() {
            const selects = document.querySelectorAll('select[name^="detalles["][name$="[id_bodega]"]');
            selects.forEach(function(select) {
                select.innerHTML = '<option value="">Seleccionar...</option>';
                if (bodegas && bodegas.length > 0) {
                    bodegas.forEach(function(bodega) {
                        select.innerHTML += '<option value="' + bodega.id_bodega + '">' + bodega.nombre + '</option>';
                    });
                }
            });
        }
        
        function loadUbicaciones(bodegaId, index) {
            const ubiSelects = document.querySelectorAll('select[name^="detalles["][name$="[id_ubicacion]"]');
            const select = ubiSelects[index];
            
            if (!bodegaId) {
                select.innerHTML = '<option value="">Seleccionar bodega...</option>';
                select.disabled = true;
                return;
            }
            
            fetch('/api/bodegas/' + bodegaId + '/ubicaciones')
                .then(response => response.json())
                .then(data => {
                    select.innerHTML = '<option value="">Seleccionar...</option>';
                    data.forEach(function(ubicacion) {
                        select.innerHTML += '<option value="' + ubicacion.id_ubicacion + '">' + ubicacion.nombre + '</option>';
                    });
                    select.disabled = false;
                });
        }
        
        function calcularFaltante(index, cantidadComprada) {
            const input = document.getElementById('cantidad_recibida_' + index);
            const faltanteSpan = document.getElementById('faltante_' + index);
            let cantidadRecibida = parseFloat(input.value) || 0;
            
            if (cantidadRecibida > cantidadComprada) {
                cantidadRecibida = cantidadComprada;
                input.value = cantidadRecibida;
                alert('La cantidad recibida no puede ser mayor a la cantidad comprada');
            }
            
            const faltante = cantidadComprada - cantidadRecibida;
            faltanteSpan.textContent = faltante.toFixed(2);
            faltanteSpan.style.color = faltante > 0 ? '#dc2626' : '#059669';
        }
        
        function submitRecepcion() {
            const sucursal = document.getElementById('id_sucursal').value;
            if (!sucursal) {
                alert('La sucursal es obligatoria');
                document.getElementById('id_sucursal').focus();
                return;
            }
            
            const bodegaSelects = document.querySelectorAll('select[name^="detalles["][name$="[id_bodega]"]');
            for (let select of bodegaSelects) {
                if (!select.value) {
                    alert('La bodega es obligatoria');
                    select.focus();
                    return;
                }
            }
            
            const ubicacionSelects = document.querySelectorAll('select[name^="detalles["][name$="[id_ubicacion]"]');
            for (let select of ubicacionSelects) {
                if (!select.value) {
                    alert('La ubicación es obligatoria');
                    select.focus();
                    return;
                }
            }
            
            const form = document.getElementById('recepcion-form');
            const formData = new FormData(form);
            
            fetch('/api/recepciones/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeRecepcionModal();
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error al guardar la recepción');
            });
        }
        
        function verRecepciones(compraId, proveedor, numeroFactura) {
            document.getElementById('ver-compra-info').textContent = 'Proveedor: ' + proveedor + ' - Factura: ' + numeroFactura;
            
            fetch('/api/recepciones/por-compra/' + compraId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Recepciones:', data);
                    renderRecepciones(data);
                    document.getElementById('ver-recepcion-modal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar las recepciones: ' + error.message);
                });
        }
        
        function renderRecepciones(recepciones) {
            const tbody = document.getElementById('recepciones-body');
            tbody.innerHTML = '';
            
            if (recepciones.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="padding: 1rem; text-align: center; color: #6b7280;">No hay recepciones registradas</td></tr>';
                return;
            }
            
            recepciones.forEach(function(recepcion) {
                let productosTexto = '';
                if (recepcion.detalles && recepcion.detalles.length > 0) {
                    productosTexto = recepcion.detalles.map(function(d) {
                        let nombre = d.producto_nombre || 'Producto';
                        let attrs = d.atributos ? ' (' + d.atributos + ')' : '';
                        return nombre + attrs;
                    }).join('<br>');
                } else {
                    productosTexto = 'Sin productos';
                }
                
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #e5e7eb';
                row.innerHTML = `
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${recepcion.fecha}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${recepcion.observacion || 'Sin observación'}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${productosTexto}</td>
                    <td style="padding: 0.75rem;">
                        <button type="button" class="btn-ver-detalle" data-id="${recepcion.id_recepcion}" style="background-color: #2563eb; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500; border: none; cursor: pointer;">
                            Ver Detalle
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            document.querySelectorAll('.btn-ver-detalle').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var idRecepcion = parseInt(this.getAttribute('data-id'));
                    verDetalleRecepcion(idRecepcion);
                });
            });
        }
        
        function verDetalleRecepcion(idRecepcion) {
            var url = '/api/recepciones/' + idRecepcion + '/detalles';
            fetch(url)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    renderDetalleRecepcion(data);
                    document.getElementById('detalle-recepcion-modal').style.display = 'block';
                });
        }
        
        function renderDetalleRecepcion(detalles) {
            const tbody = document.getElementById('detalle-recepcion-body');
            tbody.innerHTML = '';
            
            let tieneLote = false;
            let tieneVencimiento = false;
            
            detalles.forEach(function(detalle) {
                if (detalle.variante) {
                    if (detalle.variante.tiene_lote === true || detalle.variante.tiene_lote === 1) tieneLote = true;
                    if (detalle.variante.tiene_fecha_vencimiento === true || detalle.variante.tiene_fecha_vencimiento === 1) tieneVencimiento = true;
                }
            });
            
            document.getElementById('recepcion-lote-header').style.display = tieneLote ? '' : 'none';
            document.getElementById('recepcion-vencimiento-header').style.display = tieneVencimiento ? '' : 'none';
            
            detalles.forEach(function(detalle) {
                let atributosTexto = 'N/A';
                if (detalle.variante && detalle.variante.valores && detalle.variante.valores.length > 0) {
                    atributosTexto = detalle.variante.valores.map(function(v) {
                        return v.atributo.nombre + ': ' + v.valor;
                    }).join(' | ');
                }
                
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #e5e7eb';
                
                let loteCell = tieneLote ? '<td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">' + (detalle.lote || '-') + '</td>' : '';
                let vencimientoCell = tieneVencimiento ? '<td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">' + (detalle.fecha_vencimiento || '-') + '</td>' : '';
                
                row.innerHTML = '<td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">' + (detalle.variante && detalle.variante.producto ? detalle.variante.producto.nombre : 'N/A') + '</td>' +
                    '<td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">' + atributosTexto + '</td>' +
                    '<td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">' + parseFloat(detalle.cantidad_recibida).toFixed(2) + '</td>' +
                    '<td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">' + (detalle.bodega ? detalle.bodega.nombre : 'N/A') + '</td>' +
                    '<td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">' + (detalle.ubicacion ? detalle.ubicacion.nombre : 'N/A') + '</td>' +
                    loteCell + vencimientoCell;
                tbody.appendChild(row);
            });
        }
        
        function closeVerRecepcionModal() {
            document.getElementById('ver-recepcion-modal').style.display = 'none';
        }
        
        function closeDetalleRecepcionModal() {
            document.getElementById('detalle-recepcion-modal').style.display = 'none';
        }
    </script>
</x-filament-panels::page>