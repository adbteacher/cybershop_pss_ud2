<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/catalog.php';
checkRememberMe();

$conn = getDbConnection();
$id = $_GET['id'] ?? 0;
$product = $conn->query("SELECT * FROM products WHERE id = " . intval($id))->fetch_assoc();

$reviewMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $comment = $_POST['comment'] ?? '';
    $rating = intval($_POST['rating'] ?? 5);
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, comment, rating) VALUES (?, ?, ?, ?)");
    $uid = $_SESSION['user_id'];
    $stmt->bind_param('iisi', $id, $uid, $comment, $rating);
    $stmt->execute();
    $reviewMsg = 'Gracias por tu opinion.';
}

$reviews = $conn->query("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE product_id = " . intval($id) . " ORDER BY r.id DESC");

$ratingSummary = null;
$relatedProducts = [];
if ($product) {
    $ratings = catalogRatingsMap($conn);
    $ratingSummary = $ratings[$product['id']] ?? null;

    $catKey = catalogCategoryFor($product['name']);
    $allProducts = $conn->query("SELECT * FROM products");
    if ($allProducts) {
        while ($p = $allProducts->fetch_assoc()) {
            if ((int) $p['id'] !== (int) $product['id'] && catalogCategoryFor($p['name']) === $catKey) {
                $relatedProducts[] = $p;
            }
        }
    }
    $relatedProducts = array_slice($relatedProducts, 0, 4);

    $breadcrumb = [
        catalogCategoryLabel($product['name']) => '/index.php?cat=' . $catKey,
        $product['name'],
    ];
}

$pageTitle = $product['name'] ?? 'Producto';
require __DIR__ . '/includes/header.php';
?>
<?php if (!$product): ?>
    <p class="alert alert-error">Producto no encontrado.</p>
<?php else: ?>
<div class="product-detail">
    <div class="product-detail-media">
        <div class="product-thumb-large cat-<?php echo catalogCategoryFor($product['name']); ?>">
            <span><?php echo catalogCategoryIcon($product['name']); ?></span>
        </div>
    </div>
    <div class="product-detail-info">
        <span class="badge-category"><?php echo catalogCategoryLabel($product['name']); ?></span>
        <h1><?php echo $product['name']; ?></h1>
        <?php if ($ratingSummary): ?>
            <p class="stars"><?php echo catalogStars($ratingSummary['avg_r']); ?>
                <span class="rating-count"><?php echo number_format((float) $ratingSummary['avg_r'], 1); ?>/5 · <?php echo (int) $ratingSummary['n']; ?> opiniones</span></p>
        <?php else: ?>
            <p class="rating-count">Todavia sin opiniones</p>
        <?php endif; ?>
        <p class="product-description"><?php echo $product['description']; ?></p>
        <p class="price"><?php echo number_format((float) $product['price'], 2); ?> &euro;</p>
        <?php if (isset($product['stock']) && $product['stock'] > 0): ?>
            <p class="stock-info <?php echo $product['stock'] < 15 ? 'stock-low' : ''; ?>">
                <?php echo $product['stock'] < 15 ? '⚠ Quedan solo ' . $product['stock'] . ' unidades' : '✔ En stock'; ?>
            </p>
        <?php else: ?>
            <p class="stock-info stock-low">✘ Sin stock disponible</p>
        <?php endif; ?>

        <form action="/add_to_cart.php" method="POST" class="add-cart-form">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="name" value="<?php echo $product['name']; ?>">
            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
            <label>Cantidad</label>
            <input type="number" name="qty" value="1" min="1">
            <button type="submit">Añadir al carrito</button>
        </form>
        <p class="product-trust">🚚 Envio 24-48h &nbsp; · &nbsp; ↩️ Devolucion gratuita 30 dias &nbsp; · &nbsp; 🛡️ Garantia 2 años</p>
    </div>
</div>

<?php if ($relatedProducts): ?>
<h2>Tambien te puede interesar</h2>
<div class="product-grid related-grid">
    <?php foreach ($relatedProducts as $rp): ?>
    <div class="product-card">
        <a href="/product.php?id=<?php echo $rp['id']; ?>" class="product-thumb cat-<?php echo catalogCategoryFor($rp['name']); ?>">
            <span><?php echo catalogCategoryIcon($rp['name']); ?></span>
        </a>
        <a href="/product.php?id=<?php echo $rp['id']; ?>"><h3><?php echo $rp['name']; ?></h3></a>
        <p class="price"><?php echo number_format((float) $rp['price'], 2); ?> &euro;</p>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<h2>Opiniones</h2>
<?php if ($reviewMsg): ?><p class="alert alert-ok"><?php echo $reviewMsg; ?></p><?php endif; ?>
<div class="reviews">
    <?php while ($r = $reviews->fetch_assoc()): ?>
        <div class="review">
            <div class="review-head">
                <strong><?php echo $r['username']; ?></strong>
                <span class="stars"><?php echo catalogStars($r['rating']); ?></span>
                <?php if (!empty($r['created_at'])): ?><span class="review-date"><?php echo catalogTimeAgo($r['created_at']); ?></span><?php endif; ?>
            </div>
            <p><?php echo $r['comment']; ?></p>
        </div>
    <?php endwhile; ?>
</div>

<?php if (isLoggedIn()): ?>
<form method="POST" class="form-box">
    <label>Tu opinion</label>
    <textarea name="comment" required></textarea>
    <label>Valoracion (1-5)</label>
    <input type="number" name="rating" min="1" max="5" value="5">
    <button type="submit">Enviar opinion</button>
</form>
<?php else: ?>
    <p><a href="/login.php">Inicia sesion</a> para dejar una opinion.</p>
<?php endif; ?>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
