<?php
$pageTitle = 'Rezept';
$role = 'user';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0;

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/components/recipe_cards.php';
require_once __DIR__ . '/../includes/favorite_helpers.php';


$id = isset($_GET['id']) && ctype_digit($_GET['id'])
    ? (int)$_GET['id']
    : 0;

if ($id <= 0) {
    http_response_code(400);
    echo '
        <div class="container py-4">
            <p class="alert alert-warning">
                Ungültige Rezept-ID.
                <a href="recipes.php">Zurück zur Übersicht</a>
            </p>
        </div>
    ';
    include __DIR__ . '/../includes/footer.php';
    exit;
}


if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_recipe_id'])) {
    $favRecipeId = (int)$_POST['favorite_recipe_id'];

    
    if ($favRecipeId === $id) {
        toggleFavorite((int)$_SESSION['user_id'], $favRecipeId);
    }

    
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}


$rows   = getRecipesWithTags([$id]);
$recipe = $rows[$id] ?? null;

if (!$recipe) {
    http_response_code(404);
    echo '
        <div class="container py-4">
            <p class="alert alert-warning">
                Kein Rezept gefunden.
                <a href="recipes.php">Zurück zur Übersicht</a>
            </p>
        </div>
    ';
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$ingredients = getIngredientsByRecipeId($id);


$tagsFlat = [];
if (!empty($recipe['tags'])) {
    foreach ($recipe['tags'] as $t) {
        if (!empty($t['name'])) {
            $tagsFlat[] = (string)$t['name'];
        }
    }
}


$similarIds = getSimilarRecipeIdsByRecipeId($id, 3);
$similar    = !empty($similarIds)
    ? (getRecipesWithTags($similarIds) ?? [])
    : [];
?>

<main>
<div class="container">

    <div class="row justify-content-center g-4">


        <article class="col-12 col-lg-8">

            <section class="section hero my-3 my-md-4">
                <h1 class="fs-3 mb-2"><?= esc($recipe['title'] ?? 'Unbenannt') ?></h1>

                <?php if (!empty($recipe['description'])): ?>
                    <p class="mb-3 text-muted"><?= esc($recipe['description']) ?></p>
                <?php endif; ?>

                <div class="d-flex flex-wrap gap-3 small text-muted">
                    <?php if (!empty($recipe['time_min'])): ?>
                        <span>⏱ <?= (int)$recipe['time_min'] ?> min</span>
                    <?php endif; ?>

                    <?php if (!empty($recipe['servings'])): ?>
                        <span>👥 <?= (int)$recipe['servings'] ?> Portionen</span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($tagsFlat)): ?>
                    <div class="d-flex flex-wrap gap-2 my-3">
                        <?php foreach ($tagsFlat as $t): ?>
                            <span class="badge rounded-pill">
                                <?= esc($t) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <div class="d-flex gap-2 mt-3">

                        <form method="post" action="shopping_add.php" class="d-inline">
                            <input type="hidden" name="recipe_id" value="<?= (int)$id ?>">
                            <input type="hidden" name="return" value="<?= esc($_SERVER['REQUEST_URI']) ?>">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                Zur Einkaufsliste
                            </button>
                        </form>

                         <?php $isFav = isRecipeFavorited((int)$_SESSION['user_id'], (int)$id); ?>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="favorite_recipe_id" value="<?= (int)$id ?>">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <?= $isFav ? '★ Entfernen' : '☆ Favorit' ?>
                            </button>
                        </form>

                    </div>
                <?php endif; ?>

            </section>

            <section class="section bg-cream mb-3 mb-md-4">
                <div class="row g-3 align-items-start">

                    <div class="col-12 col-lg-6">
                        <img
                            src="<?= esc(BASE_URL . $recipe['picture_path']) ?>"
                            onerror="this.onerror=null;this.src='<?= esc(BASE_URL) ?>/img/placeholder_food.jpg';"
                            alt="<?= esc($recipe['title'] ?? 'Rezeptbild') ?>"
                            class="img-fluid rounded recipe-img"
                        >
                    </div>

                    <div class="col-12 col-lg-6">
                        <h2 class="fs-5 mb-3">Zutaten</h2>

                        <?php if (!empty($ingredients)): ?>
                            <ul class="mb-0">
                                <?php foreach ($ingredients as $ing): ?>
                                    <?php
                                    $qty  = trim((string)($ing['quantity'] ?? ''));
                                    $unit = trim((string)($ing['unit'] ?? ''));
                                    $name = trim((string)($ing['name'] ?? ''));
                                    $line = trim($qty . ' ' . $unit . ' ' . $name);
                                    ?>
                                    <?php if ($line !== ''): ?>
                                        <li><?= esc($line) ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted mb-0">Keine Zutaten hinterlegt.</p>
                        <?php endif; ?>
                    </div>

                </div>
            </section>

            <section class="section bg-cream mb-3 mb-md-4">
                <h2 class="fs-5 mb-3">Zubereitung</h2>

                <?php if (!empty($recipe['steps'])): ?>
                    <div><?= nl2br(esc((string)$recipe['steps'])) ?></div>
                <?php else: ?>
                    <p class="text-muted mb-0">Keine Schritte hinterlegt.</p>
                <?php endif; ?>
            </section>

        </article>

        
        <aside class="col-12 col-lg-4">
            <section class="section mt-4">
                <h2 class="fs-6 mb-4">Ähnliche Rezepte</h2>

                <?php if (!empty($similar)): ?>
                    <?php displayCompactRecipeCards($similar); ?>
                <?php else: ?>
                    <p class="text-muted small">Keine ähnlichen Rezepte gefunden.</p>
                <?php endif; ?>
            </section>
        </aside>

    </div>

</div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
