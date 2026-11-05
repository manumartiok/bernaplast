<h2>Nuevo mensaje de contacto</h2>
<p><strong>Nombre y apellido:</strong> {{ $data['nombre'] ?? '' }}</p>
<p><strong>Teléfono:</strong> {{ $data['telefono'] ?? '' }}</p>
<p><strong>Email:</strong> {{ $data['email'] ?? '' }}</p>
<p><strong>Asunto:</strong> {{ $data['asunto'] ?? '' }}</p>
<p><strong>Mensaje:</strong></p>
<p>{!! nl2br(e($data['mensaje'] ?? '')) !!}</p>
