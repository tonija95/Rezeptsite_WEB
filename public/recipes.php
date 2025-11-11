<?php
$pageTitle = 'Rezepte';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

// Tag-Optionen und Filter laden
$TAG_OPTIONS = require __DIR__ . '/../includes/tag_options.php';
require_once __DIR__ . '/../includes/filters.php';

$filters     = normalizeRecipeFilters($_GET);
$showUserFilter = ($role === 'admin');
$filtersOpen = !empty($_GET);
?>
<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Rezepte durchstöbern</h1>
    <p class="text-muted">Hier findest du Rezepte aus unserer Community – nach Lust, Laune und Geschmack.</p>
  </section>

  <!-- FILTER -->
  <section class="section bg-cream mb-3 mb-md-4 py-3 px-3">
    <div class="d-flex justify-content-between align-items-center">
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
    <div class="row g-3">

      <!-- Rezept 1 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <img src="https://picsum.photos/400/300?random=11" alt="Food image" class="card-img-top">
          <div class="card-body">
            <span class="badge me-1">Abendessen</span>
            <span class="badge me-1">Italienisch</span>
            <span class="badge me-1">Einfach</span>
            <h3 class="card-title h5 mb-2">Spaghetti Aglio e Olio</h3>
            <p class="card-text text-muted">Klassisches Pastagericht mit Knoblauch und Olivenöl – schnell gemacht.</p>
            <a href="recipe.php" class="btn btn-outline-secondary">Ansehen</a>
          </div>
        </div>
      </div>

      <!-- Rezept 2 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <img src="https://picsum.photos/400/300?random=12" alt="Food image" class="card-img-top">
          <div class="card-body">
            <span class="badge me-1">Mittagessen</span>
            <span class="badge me-1">Asiatisch</span>
            <span class="badge me-1">Proteinreich</span>
            <h3 class="card-title h5 mb-2">Ramen mit Ei</h3>
            <p class="card-text text-muted">Würzige Nudelsuppe mit Sojasoße, Gemüse und gekochtem Ei.</p>
            <a href="recipe.php" class="btn btn-outline-secondary">Ansehen</a>
          </div>
        </div>
      </div>

      <!-- Rezept 3 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <img src="https://picsum.photos/400/300?random=13" alt="Food image" class="card-img-top">
          <div class="card-body">
            <span class="badge me-1">Frühstück</span>
            <span class="badge me-1">Süß</span>
            <span class="badge me-1">Vegetarisch</span>
            <h3 class="card-title h5 mb-2">Pancakes</h3>
            <p class="card-text text-muted">Fluffige Pfannkuchen mit Ahornsirup oder Früchten – perfekt zum Start in den Tag.</p>
            <a href="recipe.php" class="btn btn-outline-secondary">Ansehen</a>
          </div>
        </div>
      </div>

      <!-- Rezept 4 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <img src="https://picsum.photos/400/300?random=14" alt="Food image" class="card-img-top">
          <div class="card-body">
            <span class="badge me-1">Snack</span>
            <span class="badge me-1">Low-Carb</span>
            <span class="badge me-1">Glutenfrei</span>
            <h3 class="card-title h5 mb-2">Gemüsechips aus dem Ofen</h3>
            <p class="card-text text-muted">Knusprige Gemüsechips aus Süßkartoffel & Zucchini – gesund und lecker.</p>
            <a href="recipe.php" class="btn btn-outline-secondary">Ansehen</a>
          </div>
        </div>
      </div>

      <!-- Rezept 5 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <img src="https://picsum.photos/400/300?random=15" alt="Food image" class="card-img-top">
          <div class="card-body">
            <span class="badge me-1">Dessert</span>
            <span class="badge me-1">Französisch</span>
            <span class="badge me-1">Anspruchsvoll</span>
            <h3 class="card-title h5 mb-2">Crème Brûlée</h3>
            <p class="card-text text-muted">Klassisches französisches Dessert mit Karamellkruste.</p>
            <a href="recipe.php" class="btn btn-outline-secondary">Ansehen</a>
          </div>
        </div>
      </div>

      <!-- Rezept 6 -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <img src="https://picsum.photos/400/300?random=16" alt="Food image" class="card-img-top">
          <div class="card-body">
            <span class="badge me-1">Abendessen</span>
            <span class="badge me-1">Mexikanisch</span>
            <span class="badge me-1">Vegan</span>
            <h3 class="card-title h5 mb-2">Bunte Veggie-Tacos</h3>
            <p class="card-text text-muted">Tacos mit würziger Gemüsefüllung – bunt, frisch und pflanzlich.</p>
            <a href="recipe.php" class="btn btn-outline-secondary">Ansehen</a>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

