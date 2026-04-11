@php
use App\Models\Compra;
$compras = \App\Models\Compra::with(['proveedor', 'detalles.variante.producto'])
    ->where('resultado_recepcion', '!=', 'Completa')
    ->orderBy('created_at', 'desc')
    ->get();
@endphp

<x-filament-panels::page>
    <div class="fi-section fi-section-content">
        <div class="fi-section-content rounded-xl bg-white dark:bg-gray-850 p-6">
            <h2 class="text-xl font-bold mb-4">Compras Pendientes de Recepción</h2>
            <p class="text-gray-600 mb-6">Seleccione una compra para registrar la recepción de productos.</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden border border-gray-200 dark:border-gray-700 rounded-xl">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="p-4 text-sm font-medium text-gray-500">Proveedor</th>
                    <th class="p-4 text-sm font-medium text-gray-500">Número Factura</th>
                    <th class="p-4 text-sm font-medium text-gray-500">Fecha Emisión</th>
                    <th class="p-4 text-sm font-medium text-gray-500">Total</th>
                    <th class="p-4 text-sm font-medium text-gray-500">Estado Recepción</th>
                    <th class="p-4 text-sm font-medium text-gray-500">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($compras as $compra)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="p-4">{{ $compra->proveedor->razon_social ?? 'N/A' }}</td>
                    <td class="p-4">{{ $compra->numero_factura ?? 'N/A' }}</td>
                    <td class="p-4">{{ $compra->fecha_emision->format('d/m/Y') }}</td>
                    <td class="p-4">${{ number_format($compra->total_neto_pagar, 2, ',', '.') }}</td>
                    <td class="p-4">
                        @php
                            $badgeClass = match($compra->resultado_recepcion) {
                                'Por recibir' => 'bg-yellow-100 text-yellow-800',
                                'Incompleta' => 'bg-red-100 text-red-800',
                                'Con daños' => 'bg-red-100 text-red-800',
                                'Mixta' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                            {{ $compra->resultado_recepcion }}
                        </span>
                    </td>
                    <td class="p-4">
                        <button 
                            type="button"
                            onclick="openRecepcionModal({{ $compra->id_compra }}, '{{ addslashes($compra->proveedor->razon_social ?? 'N/A') }}', '{{ $compra->numero_factura ?? 'N/A' }}')"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg">
                            Recepcinar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="recepcion-modal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/75 transition-opacity" onclick="closeRecepcionModal()"></div>
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">
                        Recepcinar Compra
                    </h3>
                    <p class="text-sm text-gray-500 mt-1" id="compra-info"></p>
                    
                    <form id="recepcion-form" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="id_compra" id="id_compra">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Observación</label>
                            <textarea name="observacion" id="observacion" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-3 py-2 border" placeholder="Observación opcional..."></textarea>
                        </div>
                        
                        <div class="mb-4" id="detalles-container">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Productos a Recepcinar</label>
                            <div class="overflow-hidden border border-gray-200 rounded-lg">
                                <table class="w-full text-left">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Producto</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Variante</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Cantidad Comprada</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Bodega</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Cantidad Recibida</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detalles-body" class="divide-y divide-gray-200">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="submitRecepcion()" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:ml-3 sm:w-auto">
                        Guardar Recepción
                    </button>
                    <button type="button" onclick="closeRecepcionModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
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
            
            fetch('/api/compras/' + compraId + '/detalles')
                .then(response => response.json())
                .then(data => {
                    detallesCompra = data;
                    renderDetalles(data);
                    document.getElementById('recepcion-modal').style.display = 'block';
                });
        }
        
        function closeRecepcionModal() {
            document.getElementById('recepcion-modal').style.display = 'none';
            document.getElementById('recepcion-form').reset();
            detallesCompra = [];
        }
        
        function renderDetalles(detalles) {
            const tbody = document.getElementById('detalles-body');
            tbody.innerHTML = '';
            
            detalles.forEach(function(detalle, index) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-2">` + (detalle.variante && detalle.variante.producto ? detalle.variante.producto.nombre : 'N/A') + `</td>
                    <td class="px-4 py-2">` + (detalle.variante ? detalle.variante.nombre : 'N/A') + `</td>
                    <td class="px-4 py-2">` + parseFloat(detalle.cantidad).toFixed(2) + `</td>
                    <td class="px-4 py-2">
                        <select name="detalles[` + index + `][id_bodega]" class="text-sm border-gray-300 rounded-md" onchange="loadUbicaciones(this.value, ` + index + `)" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </td>
                    <td class="px-4 py-2">
                        <select name="detalles[` + index + `][id_ubicacion]" class="text-sm border-gray-300 rounded-md" required disabled>
                            <option value="">Seleccionar bodega...</option>
                        </select>
                    </td>
                    <td class="px-4 py-2">
                        <input type="number" name="detalles[` + index + `][cantidad_recibida]" value="` + detalle.cantidad + `" min="0" step="0.01" class="text-sm border-gray-300 rounded-md w-24 px-2 py-1" required>
                        <input type="hidden" name="detalles[` + index + `][id_variante]" value="` + detalle.id_variante + `">
                        <input type="hidden" name="detalles[` + index + `][cantidad_comprada]" value="` + detalle.cantidad + `">
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            loadBodegas();
        }
        
        let bodegas = [];
        
        function loadBodegas() {
            fetch('/api/bodegas')
                .then(response => response.json())
                .then(data => {
                    bodegas = data;
                    updateBodegaSelects();
                });
        }
        
        function updateBodegaSelects() {
            const selects = document.querySelectorAll('select[name^="detalles["][name$="[id_bodega]"]');
            selects.forEach(function(select) {
                select.innerHTML = '<option value="">Seleccionar...</option>';
                bodegas.forEach(function(bodega) {
                    select.innerHTML += '<option value="' + bodega.id_bodega + '">' + bodega.nombre + '</option>';
                });
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