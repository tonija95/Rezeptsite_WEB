<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Admin-Check (neu: role, fallback: admin_logged_in)
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')
    || (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true);

if (!$isAdmin) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Rezepte verwalten (Admin)';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/filters.php';
require_once __DIR__ . '/../includes/helpers.php';

$filters = readFilters();

// Wenn Filter gesetzt → hole passende IDs und dann Rezepte
if (!empty($filters)) {
    $filteredIds = getRecipeIdsByTagFilters($filters);
    $recipes = !empty($filteredIds) ? (getRecipesWithTags($filteredIds) ?? []) : [];
} else {
    $recipes = getAllRecipesWithTags();
}

// Return-URL für delete
$returnUrl = $_SERVER['REQUEST_URI'] ?? 'admin_recipes.php';
?>

<div class="container">

  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Rezepte verwalten</h1>
    <p class="text-muted">Übersicht aller Rezepte im System – ansehen oder löschen.</p>
  </section>

  <?php displayFilterOptions(); ?>

  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <h2 class="h5 mb-3">Alle Rezepte (<?= (int)count($recipes) ?>)</h2>

    <?php if (empty($recipes)): ?>
      <p class="text-muted">Keine Rezepte gefunden.</p>
    <?php else: ?>

      <!-- Mobile: Cards -->
      <div class="d-md-none">
        <?php foreach ($recipes as $r): ?>
          <?php
            $id = (int)($r['id'] ?? 0);
            $userLabel = !empty($r['user_id']) ? ('User #' . (int)$r['user_id']) : 'Unbekannt';

            $badges = [];
            if (!empty($r['tags']) && is_array($r['tags'])) {
                foreach ($r['tags'] as $t) {
                    if (!empty($t['name'])) { $badges[] = (string)$t['name']; }
                }
            }
          ?>
          <div class="card mb-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <h3 class="h6 mb-1"><?= esc($r['title'] ?? 'Unbenannt') ?></h3>
                  <small class="text-muted"><?= esc($userLabel) ?></small>
                </div>
              </div>

              <?php if (!empty($badges)): ?>
                <div class="d-flex flex-wrap gap-1 mb-2">
                  <?php foreach (array_slice($badges, 0, 3) as $b): ?>
                    <span class="badge" style="font-size:0.7rem;"><?= esc($b) ?></span>
                  <?php endforeach; ?>
                  <?php if (count($badges) > 3): ?>
                    <small class="text-muted">+<?= (int)(count($badges) - 3) ?></small>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

              <div class="d-flex gap-2 mt-3">
                <a href="recipe.php?id=<?= esc($id) ?>" class="btn btn-outline-secondary btn-sm flex-fill">Ansehen</a>

                <form method="post" action="recipe_delete.php" class="flex-fill"
                      onsubmit="return confirm('Rezept wirklich löschen?');">
                  <input type="hidden" name="id" value="<?= esc($id) ?>">
                  <input type="hidden" name="return" value="<?= esc($returnUrl) ?>">
                  <button type="submit" class="btn btn-danger btn-sm w-100">Löschen</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Desktop: Tabelle -->
      <div class="d-none d-md-block">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Titel</th>
                <th>User</th>
                <th>Zeit (min)</th>
                <th>Portionen</th>
                <th>Tags</th>
                <th>Aktionen</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recipes as $r): ?>
                <?php
                  $id = (int)($r['id'] ?? 0);
                  $userLabel = !empty($r['user_id']) ? ('#' . (int)$r['user_id']) : '-';

                  $badges = [];
                  if (!empty($r['tags']) && is_array($r['tags'])) {
                      foreach ($r['tags'] as $t) {
                          if (!empty($t['name'])) { $badges[] = (string)$t['name']; }
                      }
                  }
                ?>
                <tr>
                  <td>
                    <strong><?= esc($r['title'] ?? 'Unbenannt') ?></strong>
                    <?php if (!empty($r['description'])): ?>
                      <br>
                      <small class="text-muted"><?= esc(mb_substr((string)$r['description'], 0, 60)) ?>...</small>
                    <?php endif; ?>
                  </td>
                  <td><?= esc($userLabel) ?></td>
                  <td><?= !empty($r['time_min']) ? (int)$r['time_min'] : 0 ?></td>
                  <td><?= !empty($r['servings']) ? (int)$r['servings'] : 0 ?></td>
                  <td>
                    <?php foreach (array_slice($badges, 0, 3) as $b): ?>
                      <span class="badge me-1"><?= esc($b) ?></span>
                    <?php endforeach; ?>
                    <?php if (count($badges) > 3): ?>
                      <small class="text-muted">+<?= (int)(count($badges) - 3) ?></small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="d-flex gap-2 flex-wrap">
                      <a href="recipe.php?id=<?= esc($id) ?>" class="btn btn-outline-secondary btn-sm">Ansehen</a>

                      <form method="post" action="recipe_delete.php" class="d-inline"
                            onsubmit="return confirm('Rezept wirklich löschen?');">
                        <input type="hidden" name="id" value="<?= esc($id) ?>">
                        <input type="hidden" name="return" value="<?= esc($returnUrl) ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Löschen</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php endif; ?>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
