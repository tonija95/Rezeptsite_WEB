<?php
$pageTitle = 'Dein Dashboard';
$role = 'user';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

// Session + Default-User
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user'])) { $_SESSION['user'] = 'anna'; }
$currentUser = $_SESSION['user'];

// Daten laden
require_once __DIR__ . '/../includes/data/recipes_store.php';
$allRecipes = recipesAll();
$myRecipes  = array_values(array_filter($allRecipes, fn($r) => ($r['user'] ?? '') === $currentUser));
$recentRecipes = array_slice(array_reverse($myRecipes), 0, 5);

// KPIs
$stats = [
  'my_recipes'     => count($myRecipes),
  'my_favorites'   => 12, // Dummy
  'views_total'    => 847, // Dummy
  'views_7d'       => 134, // Dummy
];
?>
<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Willkommen zurück, <?= htmlspecialchars($currentUser) ?>!</h1>
    <p class="text-muted">Dein persönliches Dashboard – Schnellzugriff auf deine Rezepte und Favoriten.</p>
  </section>

  <!-- KPI-Karten -->
  <section class="section mb-3 mb-md-4">
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="text-muted small mb-1">Meine Rezepte</div>
            <div class="h3 m-0"><?= (int)$stats['my_recipes'] ?></div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="text-muted small mb-1">Favoriten</div>
            <div class="h3 m-0"><?= (int)$stats['my_favorites'] ?></div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="text-muted small mb-1">Aufrufe gesamt</div>
            <div class="h3 m-0"><?= (int)$stats['views_total'] ?></div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="text-muted small mb-1">Aufrufe (7 Tage)</div>
            <div class="h3 m-0"><?= (int)$stats['views_7d'] ?></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Letzte Rezepte -->
  <section class="section bg-cream mb-3 mb-md-4 py-3 px-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="h5 m-0">Meine neuesten Rezepte</h2>
      <a href="user_my_recipes.php" class="btn btn-sm btn-outline-secondary">Alle anzeigen</a>
    </div>

    <?php if (empty($recentRecipes)): ?>
      <p class="text-muted">Du hast noch keine Rezepte erstellt.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Titel</th>
              <th class="d-none d-sm-table-cell">Zeit</th>
              <th class="d-none d-md-table-cell">Portionen</th>
              <th class="text-end">Aktionen</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentRecipes as $r): ?>
              <tr>
                <td class="fw-semibold"><?= htmlspecialchars($r['title'] ?? 'Unbenannt') ?></td>
                <td class="d-none d-sm-table-cell"><?= (int)($r['time_minutes'] ?? 0) ?> Min</td>
                <td class="d-none d-md-table-cell"><?= (int)($r['servings'] ?? 0) ?></td>
                <td class="text-end">
                  <div class="d-flex gap-1 justify-content-end">
                    <a href="recipe.php?id=<?= (int)($r['id'] ?? 0) ?>" class="btn btn-sm btn-outline-secondary">Ansehen</a>
                    <a href="user_recipe_edit.php?id=<?= (int)($r['id'] ?? 0) ?>" class="btn btn-sm btn-primary">Bearbeiten</a>
                    <form method="post" action="recipe_delete.php" class="d-inline">
                      <input type="hidden" name="id" value="<?= (int)($r['id'] ?? 0) ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Löschen</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>

  <!-- Schnellaktionen -->
  <section class="section mb-5">
    <h2 class="h5 mb-3">Schnellaktionen</h2>
    <div class="d-flex flex-wrap gap-2">
      <a href="user_recipe_edit.php" class="btn btn-primary">Neues Rezept erstellen</a>
      <a href="user_my_recipes.php" class="btn btn-outline-secondary">Meine Rezepte</a>
      <a href="user_favorites.php" class="btn btn-outline-secondary">Favoriten</a>
      <a href="recipes.php" class="btn btn-outline-secondary">Rezepte entdecken</a>
    </div>
  </section>

</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
