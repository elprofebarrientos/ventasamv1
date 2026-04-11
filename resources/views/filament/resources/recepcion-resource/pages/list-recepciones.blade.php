@php
use App\Models\Compra;
$compras = \App\Models\Compra::with(['proveedor', 'detalles.variante.producto'])
    ->where('resultado_recepcion', '!=', 'Completa')
    ->orderBy('created_at', 'desc')
    ->get();
@endphp

<x-filament-panels::page>
    <div class="fi-content">
        <div class="bg-gray-50 min-h-screen p-6">
            <div class="max-w-6xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Recepción de Compras</h2>
                                <p class="mt-1 text-sm text-gray-500 ml-4">Administra la recepción de productos comprados</p>
                            </div>
                            <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium">
                                {{ $compras->count() }} pendientes
                            </span>
                        </div>
                    </div>

                    @if($compras->isEmpty())
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay compras pendientes</h3>
                            <p class="mt-1 text-sm text-gray-500">Todas las compras han sido recepcionadas.</p>
                        </div>
                    @else
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 tracking-wider uppercase">Proveedor</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 tracking-wider uppercase">Factura</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 tracking-wider uppercase">Fecha</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 tracking-wider uppercase">Total</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 tracking-wider uppercase">Estado</th>
                                    <th class="px-8 py-4 text-right text-xs font-bold text-gray-600 tracking-wider uppercase">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compras as $compra)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="text-sm font-semibold text-gray-900">{{ $compra->proveedor->razon_social ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="text-sm text-gray-700">{{ $compra->numero_factura ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="text-sm text-gray-600">{{ $compra->fecha_emision->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="text-sm font-bold text-gray-900">${{ number_format($compra->total_neto_pagar, 2, ',', '.') }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        @php
                                            $statusConfig = match($compra->resultado_recepcion) {
                                                'Por recibir' => ['bg-yellow-100 text-yellow-700', 'bg-yellow-500'],
                                                'Incompleta' => ['bg-red-100 text-red-700', 'bg-red-500'],
                                                'Con daños' => ['bg-red-100 text-red-700', 'bg-red-500'],
                                                'Mixta' => ['bg-blue-100 text-blue-700', 'bg-blue-500'],
                                                default => ['bg-gray-100 text-gray-700', 'bg-gray-500']
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $statusConfig[1] }}"></span>
                                            <span class="text-xs font-medium {{ $statusConfig[0] }} px-2 py-1 rounded-full">
                                                {{ $compra->resultado_recepcion }}
                                            </span>
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <button 
                                            type="button"
                                            onclick="openRecepcionModal({{ $compra->id_compra }}, '{{ addslashes($compra->proveedor->razon_social ?? 'N/A') }}', '{{ $compra->numero_factura ?? 'N/A' }}')"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                            Recepcinar
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="recepcion-modal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/75 transition-opacity" onclick="closeRecepcionModal()"></div>
            <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-850 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-6xl">
                <div class="bg-white dark:bg-gray-850 px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100" id="modal-title">
                                Recepcinar Compra
                            </h3>
                            <p class="text-sm text-gray-500 mt-1" id="compra-info"></p>
                        </div>
                        <button type="button" onclick="closeRecepcionModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form id="recepcion-form" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="id_compra" id="id_compra">
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observación</label>
                            <textarea name="observacion" id="observacion" rows="2" class="fi-input block w-full rounded-md" placeholder="Observación opcional..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sucursal</label>
                            <select name="id_sucursal" id="id_sucursal" class="fi-input block w-full rounded-md" onchange="loadBodegasPorSucursal(this.value)" required>
                                <option value="">Seleccionar Sucursal...</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Productos a Recepcinar</label>
                            <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-800">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Producto</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Atributos</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cantidad</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bodega</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ubicación</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Recibida</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalles-body" class="bg-white dark:bg-gray-850 divide-y divide-gray-200 dark:divide-gray-700">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="submitRecepcion()" class="fi-btn fi-btn-primary w-full justify-center sm:ml-3 sm:w-auto">
                        Guardar Recepción
                    </button>
                    <button type="button" onclick="closeRecepcionModal()" class="fi-btn fi-btnsecondary mt-3 w-full justify-center sm:mt-0 sm:w-auto">
                        Cancelar
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
            
            detalles.forEach(function(detalle, index) {
                let atributosTexto = 'N/A';
                if (detalle.variante && detalle.variante.valores && detalle.variante.valores.length > 0) {
                    atributosTexto = detalle.variante.valores.map(function(v) {
                        return v.atributo.nombre + ': ' + v.valor;
                    }).join(' | ');
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">` + (detalle.variante && detalle.variante.producto ? detalle.variante.producto.nombre : 'N/A') + `</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">` + atributosTexto + `</td>
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">` + parseFloat(detalle.cantidad).toFixed(2) + `</td>
                    <td class="px-4 py-3">
                        <select name="detalles[` + index + `][id_bodega]" class="fi-input text-sm rounded py-1" onchange="loadUbicaciones(this.value, ` + index + `)" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        <select name="detalles[` + index + `][id_ubicacion]" class="fi-input text-sm rounded py-1" required disabled>
                            <option value="">Seleccionar bodega...</option>
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" name="detalles[` + index + `][cantidad_recibida]" value="` + detalle.cantidad + `" min="0" step="0.01" class="fi-input text-sm rounded w-20 py-1" required>
                        <input type="hidden" name="detalles[` + index + `][id_variante]" value="` + detalle.id_variante + `">
                        <input type="hidden" name="detalles[` + index + `][cantidad_comprada]" value="` + detalle.cantidad + `">
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            loadBodegasPorSucursalDesdeDetalle();
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
        
        function submitRecepcion() {
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
    </script>
</x-filament-panels::page>