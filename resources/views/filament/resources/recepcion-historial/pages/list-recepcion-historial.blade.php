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

                @if($compras->isEmpty())
                    <div style="padding: 2rem; text-align: center;">
                        <svg style="margin: 0 auto; height: 3rem; width: 3rem; color: #9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 style="margin-top: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #111827;">No hay recepciones completadas</h3>
                    </div>
                @else
                    <table style="width: 100%; border-collapse: collapse;" id="compras-table">
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
                        <tbody id="compras-body">
                            @foreach($compras as $compra)
                            <tr class="compra-row" data-proveedor="{{ strtolower($compra->proveedor->razon_social ?? '') }}">
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; font-weight: 600; color: #111827;">{{ $compra->proveedor->razon_social ?? 'N/A' }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; color: #374151;">{{ $compra->numero_factura ?? 'N/A' }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; color: #111827;">{{ $compra->created_at }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="font-size: 0.875rem; color: #111827;">${{ number_format($compra->total_neto_pagar ?? 0, 2, ',', '.') }}</div>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <span style="font-size: 0.75rem; font-weight: 500; padding: 0.25rem 0.5rem; border-radius: 9999px; background-color: #d1fae5; color: #065f46;">
                                        {{ $compra->resultado_recepcion ?? 'N/A' }}
                                    </span>
                                </td>
                                <td style="padding: 1.25rem; text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <button 
                                            type="button"
                                            onclick="verRecepciones({{ $compra->id_compra }})"
                                            style="background-color: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer;">
                                            Ver
                                        </button>
                                        <button 
                                            type="button"
                                            onclick="verDetalle({{ $compra->id_compra }})"
                                            style="background-color: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer;">
                                            Ver Detalle
                                        </button>
                                    </div>
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

    <div id="recepciones-modal" style="display: none; position: fixed; inset: 0; z-index: 60; overflow-y: auto;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div style="display: flex; min-height: 100vh; align-items: center; justify-content: center; padding: 1rem;">
            <div style="position: fixed; inset: 0; background-color: rgba(17, 24, 39, 0.75);" onclick="closeRecepcionesModal()"></div>
            <div style="position: relative; transform: translateY(0); overflow: hidden; border-radius: 0.75rem; background-color: white; text-align: left; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); width: 100%; max-width: 72rem;">
                <div style="background-color: white; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1rem;">
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Recepciones de la Compra</h3>
                            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;" id="recepciones-compra-info"></p>
                        </div>
                        <button type="button" onclick="closeRecepcionesModal()" style="color: #9ca3af;">
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
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">N° Recepción</th>
                                    <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Observación</th>
                                </tr>
                            </thead>
                            <tbody id="recepciones-list-body" style="background-color: white;">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="background-color: #f9fafb; padding: 0.75rem; display: flex; flex-direction: row-reverse; padding: 0.75rem 1.5rem;">
                    <button type="button" onclick="closeRecepcionesModal()" style="width: 100%; justify-content: center; padding: 0.5rem 1rem; background-color: #6b7280; color: white; border-radius: 0.5rem; font-weight: 500;">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="detalle-modal" style="display: none; position: fixed; inset: 0; z-index: 60; overflow-y: auto;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div style="display: flex; min-height: 100vh; align-items: center; justify-content: center; padding: 1rem;">
            <div style="position: fixed; inset: 0; background-color: rgba(17, 24, 39, 0.75);" onclick="closeDetalleModal()"></div>
            <div style="position: relative; transform: translateY(0); overflow: hidden; border-radius: 0.75rem; background-color: white; text-align: left; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); width: 100%; max-width: 72rem;">
                <div style="background-color: white; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1rem;">
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Detalle de Compra</h3>
                            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;" id="detalle-compra-info"></p>
                        </div>
                        <button type="button" onclick="closeDetalleModal()" style="color: #9ca3af;">
                            <svg style="height: 1.5rem; width: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div id="detalle-productos-container">
                        <div style="overflow: hidden; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                            <table style="width: 100%; min-width: 100%;">
                                <thead>
                                    <tr style="background-color: #f9fafb;">
                                        <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Producto</th>
                                        <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Atributos</th>
                                        <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Cantidad</th>
                                        <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Sucursal</th>
                                        <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Bodega</th>
                                        <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">Recibido</th>
                                    </tr>
                                </thead>
                                <tbody id="detalle-productos-body" style="background-color: white;">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div style="background-color: #f9fafb; padding: 0.75rem; display: flex; flex-direction: row-reverse; padding: 0.75rem 1.5rem;">
                    <button type="button" onclick="closeDetalleModal()" style="width: 100%; justify-content: center; padding: 0.5rem 1rem; background-color: #6b7280; color: white; border-radius: 0.5rem; font-weight: 500;">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filtrarProveedor(valor) {
            const searchText = valor.toLowerCase();
            const rows = document.querySelectorAll('.compra-row');
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
        
        function verRecepciones(compraId) {
            fetch('/api/recepciones/por-compra/' + compraId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('recepciones-compra-info').textContent = 'ID Compra: ' + compraId;
                    renderRecepciones(data);
                    document.getElementById('recepciones-modal').style.display = 'block';
                });
        }
        
        function renderRecepciones(recepciones) {
            const tbody = document.getElementById('recepciones-list-body');
            tbody.innerHTML = '';
            
            if (recepciones.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" style="padding: 1rem; text-align: center; color: #6b7280;">No hay recepciones registradas</td></tr>';
                return;
            }
            
            recepciones.forEach(function(recepcion) {
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #e5e7eb';
                const numeroRec = recepcion.numero_recepcion || 'RC-' + recepcion.id_recepcion;
                row.innerHTML = `
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${recepcion.fecha}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${numeroRec}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">${recepcion.observacion || 'Sin observación'}</td>
                `;
                tbody.appendChild(row);
            });
        }
        
        function closeRecepcionesModal() {
            document.getElementById('recepciones-modal').style.display = 'none';
        }
        
        function verDetalle(compraId) {
            fetch('/api/compras/' + compraId + '/detalles')
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    document.getElementById('detalle-compra-info').textContent = 'ID Compra: ' + compraId;
                    renderDetalle(data);
                    document.getElementById('detalle-modal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar el detalle: ' + error.message);
                });
        }
        
        function renderDetalle(detalles) {
            const tbody = document.getElementById('detalle-productos-body');
            tbody.innerHTML = '';
            
            detalles.forEach(function(detalle) {
                let atributosTexto = 'N/A';
                if (detalle.variante && detalle.variante.valores && detalle.variante.valores.length > 0) {
                    atributosTexto = detalle.variante.valores.map(function(v) {
                        return v.atributo.nombre + ': ' + v.valor;
                    }).join(' | ');
                }
                
                const cantidadRecibida = parseFloat(detalle.cantidad_recibida) || 0;
                const cantidad = parseFloat(detalle.cantidad);
                const cantidadPendiente = cantidad - cantidadRecibida;
                
                let recepcionesHtml = '';
                let sucursal = 'N/A';
                let bodega = 'N/A';
                let ubicacion = 'N/A';
                
                if (detalle.recepciones && detalle.recepciones.length > 0) {
                    const firstRec = detalle.recepciones[0];
                    sucursal = firstRec.sucursal || 'N/A';
                    bodega = firstRec.bodega || 'N/A';
                    ubicacion = firstRec.ubicacion || 'N/A';
                    
                    detalle.recepciones.forEach(function(rec) {
                        let recUbicacion = rec.ubicacion || 'N/A';
                        recepcionesHtml += '<div style="font-size: 0.75rem; margin-bottom: 0.25rem;">';
                        recepcionesHtml += Math.round(parseFloat(rec.cantidad_recibida)) + ' - ' + recUbicacion;
                        recepcionesHtml += '</div>';
                    });
                }
                
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #e5e7eb';
                row.innerHTML = `
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${detalle.variante && detalle.variante.producto ? detalle.variante.producto.nombre : 'N/A'}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #4b5563;">${atributosTexto}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${Math.round(cantidad)}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${sucursal}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${bodega}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: #111827;">${recepcionesHtml || (Math.round(cantidadRecibida) + ' - ' + ubicacion)}</td>
                `;
                tbody.appendChild(row);
            });
        }
        
        function closeDetalleModal() {
            document.getElementById('detalle-modal').style.display = 'none';
        }
    </script>
</x-filament-panels::page>