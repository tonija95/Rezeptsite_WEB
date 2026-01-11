<?php
$pageTitle = 'Meine Favoriten';
$role = 'user';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || (int)$_SESSION['user_id'] <= 0) {
    header('Location: index.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/filters.php';
require_once __DIR__ . '/../includes/components/recipe_cards.php';

$userId  = (int)$_SESSION['user_id'];
$filters = readFilters();

$recipeIds = getFavoriteRecipeIdsByUserId($userId);

if (!empty($recipeIds)) {
    $recipes = getRecipesWithTags($recipeIds) ?? [];
} else {
    $recipes = [];
}

if (!empty($filters)) {
    $filteredIds = getRecipeIdsByTagFilters($filters);

    if (!empty($filteredIds)) {
        $keep = array_flip($filteredIds);

        $recipes = array_filter(
            $recipes,
            fn($r) => isset($keep[(int)$r['id']])
        );
    } else {
        $recipes = [];
    }
}
?>

<main>
<div class="container">

    <section class="hero section my-3 my-md-4 d-flex flex-column gap-2">
        <h1 class="h3 mb-1">Meine Favoriten</h1>
        <p class="text-muted mb-0">
            Alle Rezepte, die du als Favorit gespeichert hast.
        </p>
    </section>

    <?php displayFilterOptions(); ?>

    <section class="section bg-cream mb-3 mb-md-4 py-3 px-3">
        <div class="row g-3">

            <?php if (!empty($recipes)): ?>
                <?php displayRecipeCard($recipes, false); ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-muted mb-0">
                        Du hast noch keine Favoriten gespeichert.
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </section>

</div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
