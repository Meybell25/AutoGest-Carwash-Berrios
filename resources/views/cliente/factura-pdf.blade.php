<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura #{{ 'FACT-' . str_pad($cita->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4facfe; padding-bottom: 20px; }
        .company-info { margin-bottom: 30px; }
        .invoice-info { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .client-info, .vehicle-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; }
        .total-row { font-weight: bold; background-color: #e9ecef; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA</h1>
        <h2>Carwash Berríos</h2>
        <p>Servicios Profesionales de Lavado y Detallado</p>
    </div>

    <div class="invoice-info">
        <div>
            <strong>Nº Factura:</strong> FACT-{{ str_pad($cita->id, 6, '0', STR_PAD_LEFT) }}<br>
            <strong>Fecha de Emisión:</strong> {{ now()->format('d/m/Y') }}<br>
            <strong>Fecha de Servicio:</strong> {{ $cita->fecha_hora->format('d/m/Y H:i') }}
        </div>
        <div>
            <strong>Estado:</strong> COMPLETADA<br>
            <strong>Método de Pago:</strong> {{ $cita->pago->metodo_formatted }}<br>
            <strong>Referencia:</strong> {{ $cita->pago->referencia ?? 'N/A' }}
        </div>
    </div>

    <div class="client-info">
        <h3>Información del Cliente</h3>
        <p>
            <strong>Nombre:</strong> {{ $cita->usuario->nombre }}<br>
            <strong>Email:</strong> {{ $cita->usuario->email }}<br>
            <strong>Teléfono:</strong> {{ $cita->usuario->telefono ?? 'No especificado' }}
        </p>
    </div>

    <div class="vehicle-info">
        <h3>Información del Vehículo</h3>
        <p>
            <strong>Vehículo:</strong> {{ $cita->vehiculo->marca }} {{ $cita->vehiculo->modelo }}<br>
            <strong>Placa:</strong> {{ $cita->vehiculo->placa ?? 'No especificada' }}<br>
            <strong>Color:</strong> {{ $cita->vehiculo->color ?? 'No especificado' }}<br>
            <strong>Tipo:</strong> {{ $cita->vehiculo->tipo_formatted ?? ucfirst($cita->vehiculo->tipo) }}
        </p>
    </div>

    <h3>Detalle de Servicios</h3>
    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Descripción</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cita->servicios as $servicio)
            <tr>
                <td>{{ $servicio->nombre }}</td>
                <td>{{ $servicio->descripcion }}</td>
                <td>${{ number_format($servicio->precio, 2) }}</td>
                <td>${{ number_format($servicio->precio, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;"><strong>SUBTOTAL:</strong></td>
                <td><strong>${{ number_format($cita->servicios->sum('precio'), 2) }}</strong></td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong></td>
                <td><strong>${{ number_format($cita->pago->monto, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($cita->observaciones)
    <div class="observations">
        <h3>Observaciones</h3>
        <p>{{ $cita->observaciones }}</p>
    </div>
    @endif

    <div class="footer">
        <p><strong>Carwash Berríos</strong></p>
        <p>Teléfono: 75855197 | Email: info@carwashberrios.com</p>
        <p>¡Gracias por su preferencia!</p>
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>