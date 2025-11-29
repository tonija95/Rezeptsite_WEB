<!-- Favoriten – Liste der Favoriten (Platzhalter) -->

<?php
$pageTitle = 'Meine Favoriten';
$role = 'user'; // temporär
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/pre datatable/get_options.php';
require_once __DIR__ . '/../includes/pre datatable/recipe_examples.php';
require_once __DIR__ . '/../includes/filters.php';
require_once __DIR__ . '/../includes/components/recipe_cards.php';

// Vorerst alle Rezepte als Favoriten (später aus DB)
$allRecipes  = getExampleRecipes();
$tagFilters  = normalizeTagFilters($_GET);
$filtered    = filterRecipesByTags($allRecipes, $tagFilters);
$filtersOpen = !empty($_GET);
?>
<div class="container">

  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Meine Favoriten</h1>
    <p class="text-muted">Hier findest du alle Rezepte, die du als Favorit markiert hast.</p>
  </section>

  <section class="section bg-cream mb-3 mb-md-4 py-3 px-3">
    <div class="d-flex justify-content-between align-items-center">
      <h2 class="h5 m-0">Filter</h2>
      <button
        class="btn btn-outline-secondary d-inline-flex align-items-center gap-1"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#recipeFilters"
        aria-expanded="<?= $filtersOpen ? 'true' : 'false' ?>"
        aria-controls="recipeFilters">
        <span><?= $filtersOpen ? 'Filter verbergen' : 'Filter anzeigen' ?></span>
        <span class="chev" aria-hidden="true">▾</span>
      </button>
    </div>

    <div id="recipeFilters" class="collapse <?= $filtersOpen ? 'show' : '' ?> mt-3">
      <?= renderTagFilterForm($tagFilters); ?>
    </div>
  </section>

  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <h2 class="h5 mb-3">Gespeicherte Favoriten</h2>
    <div class="row g-3">
      <?= renderRecipeCards(count($filtered), $filtered); ?>
      <?php if (empty($filtered)): ?>
        <div class="col-12">
          <p class="text-muted">Keine Favoriten gefunden.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>