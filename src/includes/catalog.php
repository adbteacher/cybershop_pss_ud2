<?php
// Metadatos puramente cosméticos para dar aspecto de catálogo real
// (categorías, iconos, valoraciones). No sustituye ninguna consulta
// existente: se calcula en PHP a partir de los datos ya devueltos por
// las consultas originales de cada página, para no alterar su
// comportamiento (ni el vulnerable ni el legitimo).

function catalogCategories() {
    return [
        'perifericos'    => ['label' => 'Perifericos',    'icon' => '⌨️'],
        'componentes'    => ['label' => 'Componentes',    'icon' => '🧩'],
        'almacenamiento' => ['label' => 'Almacenamiento', 'icon' => '💾'],
        'monitores'      => ['label' => 'Monitores',      'icon' => '🖥️'],
        'audio'          => ['label' => 'Audio',          'icon' => '🎧'],
        'redes'          => ['label' => 'Redes',           'icon' => '📶'],
        'oficina'        => ['label' => 'Oficina',        'icon' => '🖨️'],
        'accesorios'     => ['label' => 'Accesorios',     'icon' => '🔌'],
    ];
}

function catalogCategoryFor($productName) {
    $name = strtolower((string) $productName);
    $map = [
        'teclado' => 'perifericos', 'raton' => 'perifericos', 'ratón' => 'perifericos',
        'alfombrilla' => 'perifericos', 'webcam' => 'perifericos', 'mando' => 'perifericos',
        'auricular' => 'audio', 'casco' => 'audio', 'altavoz' => 'audio', 'altavoces' => 'audio',
        'barra de sonido' => 'audio', 'microfono' => 'audio', 'micrófono' => 'audio',
        'monitor' => 'monitores',
        'ssd' => 'almacenamiento', 'disco' => 'almacenamiento', 'nvme' => 'almacenamiento',
        'hdd' => 'almacenamiento', 'memoria usb' => 'almacenamiento', 'pendrive' => 'almacenamiento',
        'ram' => 'componentes', 'memoria ram' => 'componentes', 'grafica' => 'componentes',
        'gráfica' => 'componentes', 'procesador' => 'componentes', 'placa base' => 'componentes',
        'fuente de alimentacion' => 'componentes', 'fuente de alimentación' => 'componentes',
        'ventilador' => 'componentes', 'refrigeracion' => 'componentes', 'refrigeración' => 'componentes',
        'caja' => 'componentes',
        'router' => 'redes', 'switch' => 'redes', 'repetidor' => 'redes', 'wifi' => 'redes',
        'adaptador de red' => 'redes', 'powerline' => 'redes', 'tarjeta de red' => 'redes',
        'impresora' => 'oficina', 'escaner' => 'oficina', 'escáner' => 'oficina',
        'silla' => 'oficina', 'mesa' => 'oficina', 'lampara' => 'oficina', 'lámpara' => 'oficina',
        'hub' => 'accesorios', 'funda' => 'accesorios', 'mochila' => 'accesorios',
        'soporte' => 'accesorios', 'cable' => 'accesorios', 'adaptador' => 'accesorios',
        'cargador' => 'accesorios', 'bateria externa' => 'accesorios', 'batería externa' => 'accesorios',
    ];
    foreach ($map as $keyword => $cat) {
        if (strpos($name, $keyword) !== false) {
            return $cat;
        }
    }
    return 'accesorios';
}

function catalogCategoryLabel($productName) {
    $cats = catalogCategories();
    $key = catalogCategoryFor($productName);
    return $cats[$key]['label'] ?? 'Accesorios';
}

function catalogCategoryIcon($productName) {
    $cats = catalogCategories();
    $key = catalogCategoryFor($productName);
    return $cats[$key]['icon'] ?? '📦';
}

function catalogStars($avg) {
    $avg = (float) $avg;
    $full = (int) round($avg);
    $full = max(0, min(5, $full));
    return str_repeat('★', $full) . str_repeat('☆', 5 - $full);
}

function catalogRatingsMap($conn) {
    $ratings = [];
    $res = $conn->query("SELECT product_id, AVG(rating) AS avg_r, COUNT(*) AS n FROM reviews GROUP BY product_id");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $ratings[$row['product_id']] = $row;
        }
    }
    return $ratings;
}

function catalogTimeAgo($datetime) {
    $ts = strtotime((string) $datetime);
    if (!$ts) {
        return '';
    }
    $diff = time() - $ts;
    if ($diff < 3600) {
        return 'hace ' . max(1, floor($diff / 60)) . ' min';
    }
    if ($diff < 86400) {
        return 'hace ' . floor($diff / 3600) . ' h';
    }
    $days = floor($diff / 86400);
    if ($days < 30) {
        return 'hace ' . $days . ' dias';
    }
    if ($days < 365) {
        return 'hace ' . floor($days / 30) . ' meses';
    }
    return 'hace ' . floor($days / 365) . ' anios';
}
