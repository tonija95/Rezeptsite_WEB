<?php
$pageTitle = 'Meine Rezepte';
$role = 'user'; // temporär
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
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
    <?php 
      // ==== Filter-Setup ====
      require_once __DIR__ . '/../includes/filters.php';
      $TAG_OPTIONS = require __DIR__ . '/../includes/tag_options.php';
      $filters     = normalizeRecipeFilters($_GET);
      $showUserFilter = ($role === 'admin'); // Admin sieht User-Filter, User nicht
      $filtersOpen = !empty($_GET);
    ?>
    <h2 class="h5 m-0">Filter</h2>
    <button class="btn btn-outline-secondary d-inline-flex align-items-center gap-1"
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
    <?php include __DIR__ . '/../includes/components/filter_recipes.php'; ?>
  </div>
</section>

  <!-- Rezeptkarten -->
  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <h2 class="h5 mb-3">Deine gespeicherten Rezepte</h2>
    <div class="row g-3">

      <?php for ($i = 1; $i <= 8; $i++): ?>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="card h-100 d-flex">
            <img
              src="https://picsum.photos/400/300?random=<?= $i + 10 ?>"
              onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
              alt="Rezeptbild" class="card-img-top">
            <div class="card-body d-flex flex-column">
              <div class="mb-2">
                <span class="badge me-1">Pasta</span>
                <span class="badge me-1">Schnell</span>
              </div>
              <h3 class="card-title h5 mb-2">Mein Rezept <?= $i ?></h3>
              <p class="card-text text-muted small mb-3">Kurze Beschreibung des Rezepts …</p>

              <div class="mt-auto d-flex gap-2">
                <a href="recipe.php?id=<?= $i ?>" class="btn btn-outline-secondary btn-sm">Ansehen</a>
                <a href="user_recipe-edit.php?id=<?= $i ?>" class="btn btn-primary btn-sm">Bearbeiten</a>
                <a href="user_recipe_delete.php?id=<?= $i ?>" class="btn btn-danger btn-sm">Löschen</a>
              </div>
            </div>
          </div>
        </div>
      <?php endfor; ?>

    </div>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
