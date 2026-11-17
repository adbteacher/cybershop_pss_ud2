<?php
header('Content-Type: application/json');
echo json_encode([
    'service' => 'internal-metadata-sim',
    'role' => 'backup-service',
    'api_key_simulada' => 'AKIA_FAKE_EXAMPLE_1234567890',
    'codigo_verificacion' => 'LAB-SSRF-9f2b6e1a04',
    'mensaje' => 'Este servicio solo deberia ser accesible desde el contenedor web. Si estas leyendo esto desde fuera, una vulnerabilidad SSRF ha sido explotada con exito.'
]);
