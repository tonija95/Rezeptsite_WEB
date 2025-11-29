<?php
$pageTitle = 'Startseite';
$role = 'guest';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/data/recipes_store.php';
require_once __DIR__ . '/../includes/pre datatable/get_options.php';

// Alle Rezepte laden
$allRecipes = recipesAll();

// Tag-Optionen laden um korrekte Namen zu verwenden
$tagOptions = getTagOptions();
$mealTags = $tagOptions['meal'] ?? [];

// 1) Neuestes Rezept (zuletzt geändert/erstellt)
$newestRecipe = !empty($allRecipes) ? end($allRecipes) : null;

// 2) Random Gericht passend zur Tageszeit
$hour = (int)date('H');
if ($hour >= 6 && $hour < 11) {
    // Suche nach "Frühstück" Tag
    $mealType = 'Frühstück';
} elseif ($hour >= 11 && $hour < 15) {
    // Suche nach "Mittagessen" oder "Hauptgericht" Tag
    $mealType = 'Hauptgericht';
} elseif ($hour >= 15 && $hour < 22) {
    // Suche nach "Abendessen" oder "Hauptgericht" Tag
    $mealType = 'Hauptgericht';
} else {
    // Suche nach "Snack" oder "Vorspeise" Tag
    $mealType = 'Vorspeise';
}

$mealRecipes = array_values(array_filter($allRecipes, function($r) use ($mealType) {
    if (empty($r['tags']['meal'])) return false;
    $meals = is_array($r['tags']['meal']) ? $r['tags']['meal'] : [$r['tags']['meal']];
    return in_array($mealType, $meals, true);
}));
$randomMeal = !empty($mealRecipes) ? $mealRecipes[array_rand($mealRecipes)] : null;

// Fallback: irgendein zufälliges Rezept
if (!$randomMeal && !empty($allRecipes)) {
    $randomMeal = $allRecipes[array_rand($allRecipes)];
}

// 3) Random Dessert
$dessertRecipes = array_values(array_filter($allRecipes, function($r) {
    if (empty($r['tags']['meal'])) return false;
    $meals = is_array($r['tags']['meal']) ? $r['tags']['meal'] : [$r['tags']['meal']];
    return in_array('Dessert', $meals, true);
}));
$randomDessert = !empty($dessertRecipes) ? $dessertRecipes[array_rand($dessertRecipes)] : null;

// Fallback: irgendein zufälliges Rezept
if (!$randomDessert && !empty($allRecipes)) {
    $randomDessert = $allRecipes[array_rand($allRecipes)];
}

// Hilfsfunktion für Rezept-Karte
function renderIndexCard(?array $r, string $title, string $subtitle = ''): string {
    if (!$r) {
        return '<div class="col-12 col-md-4"><div class="card h-100"><div class="card-body"><h3 class="h6">'
            . htmlspecialchars($title) . '</h3><p class="text-muted">Keine passenden Rezepte gefunden.</p></div></div></div>';
    }
    
    $img = !empty($r['image_url']) ? htmlspecialchars($r['image_url']) : 'img/placeholder_food.jpg';
    $recipeTitle = htmlspecialchars($r['title'] ?? 'Unbenannt');
    $desc = !empty($r['description']) ? htmlspecialchars(mb_substr($r['description'], 0, 80)) . '...' : '';
    $id = (int)($r['id'] ?? 0);
    
    ob_start();
    ?>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <img class="card-img-top" src="<?= $img ?>" onerror="this.onerror=null;this.src='img/placeholder_food.jpg';" alt="">
            <div class="card-body d-flex flex-column">
                <div class="text-muted small mb-1"><?= htmlspecialchars($title) ?></div>
                <h3 class="h6 mb-2"><?= $recipeTitle ?></h3>
                <?php if ($desc): ?>
                    <p class="text-muted small mb-3"><?= $desc ?></p>
                <?php endif; ?>
                <?php if ($subtitle): ?>
                    <p class="text-muted small mb-3"><em><?= htmlspecialchars($subtitle) ?></em></p>
                <?php endif; ?>
                <div class="mt-auto">
                    <a href="recipe.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-sm">Ansehen</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>

<div class="container">
    <!-- Hero -->
    <section class="hero section my-3 my-md-4 text-center">
        <h1 class="display-6 mb-3">Willkommen auf unserer Rezeptseite!</h1>
        <p class="lead text-muted mb-4">Entdecke neue Lieblingsrezepte – frisch, vielfältig und einfach nachzukochen.</p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="recipes.php" class="btn btn-primary">Alle Rezepte entdecken</a>
            <a href="user_recipe_edit.php" class="btn btn-outline-secondary">Eigenes Rezept erstellen</a>
        </div>
    </section>

    <!-- Featured Rezepte -->
    <section class="section bg-cream mb-3 mb-md-4 py-4 px-3">
        <h2 class="h4 mb-3">Für dich ausgewählt</h2>
        <div class="row g-3">
            <?= renderIndexCard($newestRecipe, '🆕 Neuestes Rezept', 'Zuletzt hinzugefügt') ?>
            <?= renderIndexCard($randomMeal, '🕐 Passend zur Tageszeit', $mealType) ?>
            <?= renderIndexCard($randomDessert, '🍰 Zufälliges Dessert', 'Zum Nachtisch') ?>
        </div>
    </section>

    <!-- Call-to-Action -->
    <section class="section text-center mb-5">
        <h2 class="h5 mb-3">Werde Teil unserer Community!</h2>
        <p class="text-muted mb-3">Teile deine Lieblingsrezepte und entdecke neue Gerichte von anderen Köchen.</p>
        <a href="user_recipe_edit.php" class="btn btn-primary">Jetzt Rezept hochladen</a>
    </section>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
