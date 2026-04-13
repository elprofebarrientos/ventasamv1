<x-filament-panels::page>
    <div style="background-color: #f9fafb; min-height: 100vh; padding: 1.5rem;">
        <div style="max-width: 72rem; margin: 0 auto;">
            <div style="background-color: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h2 style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">Historial de Recepciones</h2>
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: #6b7280; margin-left: 1rem;">Visualiza las recepciones completadas</p>
                        </div>
                    </div>
                </div>

                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="flex: 1; max-width: 300px;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Buscar Proveedor</label>
                            <input 
                                type="text" 
                                id="searchProveedor"
                                oninput="filtrarProveedor(this.value)"
                                placeholder="Ingrese nombre del proveedor..."
                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
                            >
                        </div>
                        <div style="max-width: 200px;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Ordenar por Fecha</label>
                            <select 
                                wire:model="sortOrder"
                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
                            >
                                <option value="desc">Más reciente</option>
                                <option value="asc">Más antiguo</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if($recepciones->isEmpty())
                    <div style="padding: 2rem; text-align: center;">
                        <svg style="margin: 0 auto; height: 3rem; width: 3rem; color: #9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 style="margin-top: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #111827;">No hay recepciones completadas</h3>
                    </div>
                @else
                    <table style="width: 100%; border-collapse: collapse;" id="recepciones-table">
                        <thead>
                            <tr style="background-color: #f3f4f6;">
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Fecha</th>
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Proveedor</th>
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Factura</th>
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Observación</th>
                                <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Productos</th>
                                <th style="padding: 1rem; text-align: right; font-size: 0.75rem; font-weight: 700; color: #4b5563; text-transform: uppercase;">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="recepciones-body">
                            @foreach($recepciones as $recepcion)
                            <tr class="recepcion-row" data-proveedor="{{ strtolower($recepcion->compra->proveedor->razon_social ?? '') }}">
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; color: #111827;">{{ $recepcion->fecha }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; font-weight: 600; color: #111827;">{{ $recepcion->compra->proveedor->razon_social ?? 'N/A' }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; color: #374151;">{{ $recepcion->compra->numero_factura ?? 'N/A' }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; color: #4b5563;">{{ $recepcion->observacion ?? 'Sin observación' }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; color: #111827;">{{ $recepcion->detalles->count() }} productos</div>
                                </td>
                                <td style="padding: 1.25rem; text-align: right;">
                                    <button 
                                        type="button"
                                        onclick="verDetalleRecepcion({{ $recepcion->id_recepcion }})"
                                        style="background-color: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer;">
                                        Ver Detalle
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div id="no-results" style="display: none; padding: 2rem; text-align: center;">
                        <h3 style="font-size: 0.875rem; font-weight: 500; color: #111827;">No se encontraron resultados</h3>
                    </div>
                @endif
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
        function filtrarProveedor(valor) {
            const searchText = valor.toLowerCase();
            const rows = document.querySelectorAll('.recepcion-row');
            let visibleCount = 0;
            
            rows.forEach(function(row) {
                const proveedor = row.getAttribute('data-proveedor');
                if (proveedor.includes(searchText)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            document.getElementById('no-results').style.display = visibleCount === 0 ? 'block' : 'none';
        }
        
        function verDetalleRecepcion(idRecepcion) {
            fetch('/api/recepciones/' + idRecepcion + '/detalles')
                .then(response => response.json())
                .then(data => {
                    renderDetalleRecepcion(data);
                    document.getElementById('detalle-recepcion-modal').style.display = 'block';
                });
        }
        
        function renderDetalleRecepcion(detalles) {
            const tbody = document.getElementById('detalle-recepcion-body');
            tbody.innerHTML = '';
            
            detalles.forEach(function(detalle) {
                let atributosTexto = 'N/A';
                if (detalle.variante && detalle.variante.valores && detalle.variante.valores.length > 0) {
                    atributosTexto = detalle.variante.valores.map(function(v) {
                        return v.atributo.nombre + ': ' + v.valor;
                    }).join(' | ');
                }
                
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #e5e7eb';
                row.innerHTML = `
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${detalle.variante && detalle.variante.producto ? detalle.variante.producto.nombre : 'N/A'}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">${atributosTexto}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${parseFloat(detalle.cantidad_recibida).toFixed(2)}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">${detalle.bodega ? detalle.bodega.nombre : 'N/A'}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">${detalle.ubicacion ? detalle.ubicacion.nombre : 'N/A'}</td>
                `;
                tbody.appendChild(row);
            });
        }
        
        function closeDetalleRecepcionModal() {
            document.getElementById('detalle-recepcion-modal').style.display = 'none';
        }
    </script>
</x-filament-panels::page>