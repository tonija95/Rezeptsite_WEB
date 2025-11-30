<?php
// filepath: c:\xampp\htdocs\rezeptsite\public\recipes.php
$pageTitle = 'Alle Rezepte';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Daten / Funktionen laden
require_once __DIR__ . '/../includes/filters.php';
require_once __DIR__ . '/../includes/components/recipe_cards.php';

// Primäre Datenquellen
$storeRecipes   = [];
$exampleRecipes = [];

// Laden aus persistentem Store (falls vorhanden)
if (file_exists(__DIR__ . '/../includes/data/recipes_store.php')) {
    require_once __DIR__ . '/../includes/data/recipes_store.php';
    if (function_exists('recipesAll')) {
        $storeRecipes = recipesAll();
    }
}

// Fallback / Demo-Daten
if (file_exists(__DIR__ . '/../includes/pre datatable/recipe_examples.php')) {
    require_once __DIR__ . '/../includes/pre datatable/recipe_examples.php';
    if (function_exists('getExampleRecipes')) {
        $exampleRecipes = getExampleRecipes();
    }
}

// Zusammenführen (Store bevorzugt, Beispiele ergänzen falls Id nicht doppelt)
if (!empty($storeRecipes)) {
    $allRecipes = $storeRecipes;
} else {
    $allRecipes = $exampleRecipes;
}

// Filter normalisieren + anwenden
$tagFilters      = normalizeTagFilters($_GET ?? []);
$filteredRecipes = filterRecipesByTags($allRecipes, $tagFilters);
?>
<div class="container">

  <section class="hero section my-3 my-md-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
    <div>
      <h1 class="h3 mb-2">Alle Rezepte</h1>
      <p class="text-muted mb-0">Stöbere in allen verfügbaren Rezepten und nutze die Filter.</p>
    </div>
  </section>

  <?= renderTagFilterSection($tagFilters) ?>

  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h2 class="h6 mb-0">Ergebnisse</h2>
      <span class="text-muted small"><?= count($filteredRecipes) ?> / <?= count($allRecipes) ?> Treffer</span>
    </div>
    <div class="row g-3">
      <?= renderRecipeCards(count($filteredRecipes), $filteredRecipes, ['view']); ?>
      <?php if (empty($filteredRecipes)): ?>
        <div class="col-12"><p class="text-muted mb-0">Keine Rezepte gefunden. Filter anpassen.</p></div>
      <?php endif; ?>
    </div>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

