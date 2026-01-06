<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id']) || (int)$_SESSION['user_id'] <= 0) {
    header('Location: index.php');
    exit;
}


$pageTitle = 'Meine Rezepte';
$role = 'user';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/filters.php';
require_once __DIR__ . '/../includes/components/recipe_cards.php';

$userId  = (int)$_SESSION['user_id'];
$filters = readFilters();

$recipeIds = getRecipeIdsByUserId($userId);

if (empty($recipeIds)) {
    $recipes = [];
} else {
    $recipes = getRecipesWithTags($recipeIds) ?? [];
}

if (!empty($filters)) {
    $filteredIds = getRecipeIdsByTagFilters($filters);

    if (empty($filteredIds)) {
        $recipes = [];
    } else {
        $keep = array_flip($filteredIds);

        $recipes = array_filter(
            $recipes,
            fn($r) => isset($keep[(int)$r['id']])
        );
    }
}
?>

<div class="container">

    <section class="hero section my-3 my-md-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
        <div>
            <h1 class="h3 mb-2">Meine Rezepte</h1>
            <p class="text-muted mb-0">Alle Rezepte, die du selbst erstellt hast.</p>
        </div>
        <a href="recipe_edit.php" class="btn btn-primary">Neues Rezept erstellen</a>
    </section>

    <?php displayFilterOptions(); ?>

    <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
        <div class="row g-3">
            <?php if (!empty($recipes)): ?>
                <?php displayRecipeCard($recipes, true); ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-muted">Keine Rezepte gefunden.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
