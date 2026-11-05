<h2>Nueva solicitud de presupuesto</h2>
<p><strong>Nombre y apellido:</strong> {{ $data['nombre'] ?? '' }}</p>
<p><strong>Teléfono:</strong> {{ $data['telefono'] ?? '' }}</p>
<p><strong>Email:</strong> {{ $data['email'] ?? '' }}</p>

<h3>Productos</h3>
<table cellpadding="6" cellspacing="0" border="1" style="border-collapse: collapse;">
    <tr>
        <th align="left">Código</th>
        <th align="left">Producto</th>
        <th align="left">Tapa</th>
        <th align="left">Color</th>
        <th align="right">Cantidad</th>
    </tr>
    @foreach ($items as $item)
        <tr>
            <td>{{ $item['producto']->codigoDeTapa($item['tapa'], $item['color']) ?: ($item['producto']->codigo ?: '-') }}</td>
            <td>{{ $item['producto']->t('nombre') }}</td>
            <td>{{ $item['tapa'] ?: '-' }}</td>
            <td>{{ $item['color'] ?: '-' }}</td>
            <td align="right">{{ $item['cantidad'] }}</td>
        </tr>
    @endforeach
</table>

<p>{{ isset($data['archivo']) ? 'El cliente adjuntó un archivo.' : '' }}</p>
