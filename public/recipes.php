<?php

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user'])) { $_SESSION['user'] = 'anna'; }

require_once __DIR__ . '/../includes/data/recipes_store.php';
require_once __DIR__ . '/../includes/filters.php';
require_once __DIR__ . '/../includes/pre datatable/get_options.php';

$pageTitle = 'Rezepte';
$role = 'guest';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

$allRecipes = recipesAll();
$filters    = normalizeTagFilters($_GET ?? []);
$recipes    = filterRecipesByTags($allRecipes, $filters);
?>
<div class="container">
  <section class="hero section my-3 my-md-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
    <div>
      <h1 class="h3 mb-2">Alle Rezepte</h1>
      <p class="text-muted mb-0">Entdecke alle vorhandenen Rezepte.</p>
    </div>
  </section>

  <?= renderTagFilterSection($filters) ?>

  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <div class="row g-3">
      <?php foreach ($recipes as $r): ?>
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card h-100">
            <?php $img = !empty($r['image_url']) ? $r['image_url'] : 'img/placeholder_food.jpg'; ?>
            <img class="card-img-top" src="<?= htmlspecialchars($img) ?>" onerror="this.onerror=null;this.src='img/placeholder_food.jpg';" alt="">
            <div class="card-body d-flex flex-column">
              <h3 class="h6 mb-2"><?= htmlspecialchars($r['title'] ?? 'Unbenannt') ?></h3>
              <?php if (!empty($r['description'])): ?>
                <p class="text-muted small mb-3"><?= htmlspecialchars($r['description']) ?></p>
              <?php endif; ?>
              <div class="mt-auto d-flex gap-2">
                <a class="btn btn-outline-secondary btn-sm" href="recipe.php?id=<?= (int)($r['id'] ?? 0) ?>">Ansehen</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($recipes)): ?>
        <div class="col-12"><p class="text-muted">Keine Rezepte gefunden.</p></div>
      <?php endif; ?>
    </div>
  </section>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

