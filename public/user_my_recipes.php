<?php
$pageTitle = 'Meine Rezepte';
$role = 'user'; // temporär
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/pre datatable/get_options.php';
require_once __DIR__ . '/../includes/pre datatable/recipe_examples.php';
require_once __DIR__ . '/../includes/filters.php';
require_once __DIR__ . '/../includes/components/recipe_cards.php';

// Aktueller User (später aus Session)
$currentUser = 'anna'; // temporär

$allRecipes  = getExampleRecipes();
$tagFilters  = normalizeTagFilters($_GET);

// Filter um aktuellen User erweitern (nur eigene Rezepte)
$myRecipes = array_values(array_filter($allRecipes, fn($r) => isset($r['user']) && $r['user'] === $currentUser));
$filtered  = filterRecipesByTags($myRecipes, $tagFilters);
$filtersOpen = !empty($_GET);
?>
<div class="container">

  <!-- Hero / Seitentitel -->
  <section class="hero section my-3 my-md-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
    <div>
      <h1 class="h3 mb-2">Meine Rezepte</h1>
      <p class="text-muted mb-0">Hier findest du alle Rezepte, die du erstellt hast.</p>
    </div>
    <a href="user_recipe-edit.php" class="btn btn-primary">Neues Rezept erstellen</a>
  </section>

  <!-- FILTER: großer Dropdown-Bereich + Unter-Dropdowns je Kategorie -->
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

  <!-- Rezeptkarten -->
  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <h2 class="h5 mb-3">Deine gespeicherten Rezepte</h2>
    <div class="row g-3">
      <?php if (empty($filtered)): ?>
        <p class="text-muted">Keine Rezepte gefunden.</p>
      <?php else: ?>
        <?php foreach ($filtered as $r): ?>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100">
              <?php $img = !empty($r['image_url']) ? $r['image_url'] : 'img/placeholder_food.jpg'; ?>
              <img src="<?= esc($img) ?>" onerror="this.onerror=null;this.src='img/placeholder_food.jpg';" alt="Rezeptbild" class="card-img-top">
              <div class="card-body d-flex flex-column">
                <?php
                  $badges = [];
                  if (!empty($r['tags']) && is_array($r['tags'])) {
                    foreach ($r['tags'] as $vals) {
                      if (is_array($vals)) foreach ($vals as $v) $badges[] = esc($v);
                      else $badges[] = esc($vals);
                    }
                  }
                ?>
                <div class="mb-2">
                  <?php foreach (array_slice($badges, 0, 3) as $b): ?>
                    <span class="badge me-1"><?= $b ?></span>
                  <?php endforeach; ?>
                </div>
                <h3 class="card-title h5 mb-2"><?= esc($r['title'] ?? 'Unbenannt') ?></h3>
                <?php if (!empty($r['description'])): ?>
                  <p class="card-text text-muted small mb-3"><?= esc($r['description']) ?></p>
                <?php endif; ?>

                <div class="mt-auto d-flex gap-2">
                  <a href="recipe.php?id=<?= (int)($r['id'] ?? 0) ?>" class="btn btn-outline-secondary btn-sm">Ansehen</a>
                  <a href="user_recipe-edit.php?id=<?= (int)($r['id'] ?? 0) ?>" class="btn btn-primary btn-sm">Bearbeiten</a>
                  <a href="user_recipe_delete.php?id=<?= (int)($r['id'] ?? 0) ?>" class="btn btn-danger btn-sm">Löschen</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
