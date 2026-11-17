<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/catalog.php';
checkRememberMe();

$conn = getDbConnection();
$search = $_GET['q'] ?? '';

if ($search !== '') {
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM products";
}
$result = $conn->query($sql);

$products = [];
if ($result !== false) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$activeCat = $_GET['cat'] ?? '';
$categories = catalogCategories();
if ($activeCat !== '' && isset($categories[$activeCat])) {
    $products = array_values(array_filter($products, function ($p) use ($activeCat) {
        return catalogCategoryFor($p['name']) === $activeCat;
    }));
}

$ratings = catalogRatingsMap($conn);

$bestsellers = $products;
usort($bestsellers, function ($a, $b) use ($ratings) {
    $ra = $ratings[$a['id']]['avg_r'] ?? 0;
    $rb = $ratings[$b['id']]['avg_r'] ?? 0;
    return $rb <=> $ra;
});
$bestsellers = array_slice($bestsellers, 0, 4);

$testimonials = $conn->query(
    "SELECT r.comment, r.rating, u.username, p.name AS product_name " .
    "FROM reviews r JOIN users u ON r.user_id = u.id JOIN products p ON r.product_id = p.id " .
    "WHERE r.rating >= 4 ORDER BY r.id DESC LIMIT 3"
);

$pageTitle = 'Inicio';
require __DIR__ . '/includes/header.php';
?>

<?php if ($search === '' && $activeCat === ''): ?>
<section class="hero">
    <div class="hero-text">
        <span class="hero-tag">Ofertas de temporada</span>
        <h1>Todo lo que necesitas para tu setup, en un solo sitio</h1>
        <p>Perifericos, componentes, monitores y mucho mas con envio en 24-48h y garantia de 2 años.</p>
        <a href="/index.php?cat=perifericos" class="hero-cta">Ver ofertas destacadas</a>
    </div>
    <div class="hero-art" aria-hidden="true">🖥️🎧⌨️🖱️</div>
</section>

<section class="trust-bar">
    <div class="trust-item">🚚 <span>Envio 24-48h</span></div>
    <div class="trust-item">🔒 <span>Pago 100% seguro</span></div>
    <div class="trust-item">↩️ <span>Devolucion gratuita 30 dias</span></div>
    <div class="trust-item">🎧 <span>Atencion al cliente 24/7</span></div>
</section>
<?php endif; ?>

<?php if ($bestsellers && $search === '' && $activeCat === ''): ?>
<h2>Los favoritos de nuestros clientes</h2>
<div class="product-grid bestseller-grid">
    <?php foreach ($bestsellers as $row): $rating = $ratings[$row['id']] ?? null; ?>
    <div class="product-card">
        <a href="/product.php?id=<?php echo $row['id']; ?>" class="product-thumb cat-<?php echo catalogCategoryFor($row['name']); ?>">
            <span><?php echo catalogCategoryIcon($row['name']); ?></span>
        </a>
        <span class="badge-category"><?php echo catalogCategoryLabel($row['name']); ?></span>
        <a href="/product.php?id=<?php echo $row['id']; ?>">
            <h3><?php echo $row['name']; ?></h3>
        </a>
        <?php if ($rating): ?>
            <p class="stars"><?php echo catalogStars($rating['avg_r']); ?> <span class="rating-count">(<?php echo (int) $rating['n']; ?>)</span></p>
        <?php endif; ?>
        <p class="price"><?php echo number_format((float) $row['price'], 2); ?> &euro;</p>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<h1><?php echo $activeCat !== '' && isset($categories[$activeCat]) ? $categories[$activeCat]['label'] : 'Nuestros productos'; ?></h1>
<?php if ($search !== ''): ?>
    <p class="search-summary">Resultados para "<?php echo $search; ?>" (<?php echo count($products); ?>)</p>
<?php endif; ?>

<?php if ($result === false): ?>
    <p class="alert alert-error">Error en la consulta: <?php echo $conn->error; ?></p>
<?php elseif (empty($products)): ?>
    <p>No se han encontrado productos con esos criterios.</p>
<?php else: ?>
<div class="product-grid">
    <?php foreach ($products as $row): $rating = $ratings[$row['id']] ?? null; ?>
    <div class="product-card">
        <a href="/product.php?id=<?php echo $row['id']; ?>" class="product-thumb cat-<?php echo catalogCategoryFor($row['name']); ?>">
            <span><?php echo catalogCategoryIcon($row['name']); ?></span>
        </a>
        <span class="badge-category"><?php echo catalogCategoryLabel($row['name']); ?></span>
        <?php if (isset($row['stock']) && is_numeric($row['stock']) && $row['stock'] > 0 && $row['stock'] < 15): ?>
            <span class="badge-stock">¡Ultimas <?php echo $row['stock']; ?> unidades!</span>
        <?php endif; ?>
        <a href="/product.php?id=<?php echo $row['id']; ?>">
            <h3><?php echo $row['name']; ?></h3>
        </a>
        <p class="product-desc"><?php echo $row['description']; ?></p>
        <?php if ($rating): ?>
            <p class="stars"><?php echo catalogStars($rating['avg_r']); ?> <span class="rating-count">(<?php echo (int) $rating['n']; ?>)</span></p>
        <?php endif; ?>
        <p class="price"><?php echo number_format((float) $row['price'], 2); ?> &euro;</p>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ($testimonials && $testimonials->num_rows > 0): ?>
<section class="testimonials">
    <h2>Lo que dicen nuestros clientes</h2>
    <div class="testimonial-grid">
        <?php while ($t = $testimonials->fetch_assoc()): ?>
        <div class="testimonial-card">
            <p class="stars"><?php echo catalogStars($t['rating']); ?></p>
            <p>&ldquo;<?php echo $t['comment']; ?>&rdquo;</p>
            <p class="testimonial-author">— <?php echo $t['username']; ?>, sobre <?php echo $t['product_name']; ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($search === '' && $activeCat === ''): ?>
<section class="stats-bar">
    <div><strong>+18.000</strong><span>pedidos entregados</span></div>
    <div><strong>4.8/5</strong><span>valoracion media</span></div>
    <div><strong>98%</strong><span>clientes satisfechos</span></div>
    <div><strong>8</strong><span>categorias de producto</span></div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
