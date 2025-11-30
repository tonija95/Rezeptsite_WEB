<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Rezepte verwalten (Admin)';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

// Session + Admin-Rolle setzen
$_SESSION['role'] = 'admin';

require_once __DIR__ . '/../includes/pre datatable/get_options.php';
require_once __DIR__ . '/../includes/data/recipes_store.php';
require_once __DIR__ . '/../includes/filters.php';

$allRecipes  = recipesAll();
$tagFilters  = normalizeTagFilters($_GET);
$filtered    = filterRecipesByTags($allRecipes, $tagFilters);
?>
<div class="container">

  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Rezepte verwalten</h1>
    <p class="text-muted">Übersicht aller Rezepte im System – bearbeiten, löschen oder Details ansehen.</p>
  </section>

  <?= renderTagFilterSection($tagFilters) ?>

  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <h2 class="h5 mb-3">Alle Rezepte (<?= count($filtered) ?>)</h2>
    
    <?php if (empty($filtered)): ?>
      <p class="text-muted">Keine Rezepte gefunden.</p>
    <?php else: ?>
      
      <!-- Mobile: Card-Layout -->
      <div class="d-md-none">
        <?php foreach ($filtered as $r): ?>
          <?php
            $id = (int)($r['id'] ?? 0);
            $badges = [];
            if (!empty($r['tags']) && is_array($r['tags'])) {
              foreach ($r['tags'] as $vals) {
                if (is_array($vals)) {
                  foreach ($vals as $v) {
                    $v = trim((string)$v);
                    if ($v !== '') $badges[] = htmlspecialchars($v);
                  }
                } else {
                  $v = trim((string)$vals);
                  if ($v !== '') $badges[] = htmlspecialchars($v);
                }
              }
            }
          ?>
          <div class="card mb-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <h3 class="h6 mb-1"><?= htmlspecialchars($r['title'] ?? 'Unbenannt') ?></h3>
                  <small class="text-muted">@<?= htmlspecialchars($r['user'] ?? 'Unbekannt') ?></small>
                </div>
              </div>
              <div class="d-flex gap-2 mt-3">
                <a href="recipe.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-sm flex-fill">Ansehen</a>
                <!-- Zentraler Delete: POST an recipe_delete.php -->
                <form method="post" action="recipe_delete.php" class="flex-fill">
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <button type="submit" class="btn btn-danger btn-sm w-100">Löschen</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Desktop: Tabellen-Layout -->
      <div class="d-none d-md-block">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Titel</th>
                <th>User</th>
                <th>Zeit (Min)</th>
                <th>Portionen</th>
                <th>Tags</th>
                <th>Aktionen</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($filtered as $r): ?>
                <?php
                  $id = (int)($r['id'] ?? 0);
                  $badges = [];
                  if (!empty($r['tags']) && is_array($r['tags'])) {
                    foreach ($r['tags'] as $vals) {
                      if (is_array($vals)) {
                        foreach ($vals as $v) {
                          $v = trim((string)$v);
                          if ($v !== '') $badges[] = htmlspecialchars($v);
                        }
                      } else {
                        $v = trim((string)$vals);
                        if ($v !== '') $badges[] = htmlspecialchars($v);
                      }
                    }
                  }
                ?>
                <tr>
                  <td>
                    <strong><?= htmlspecialchars($r['title'] ?? 'Unbenannt') ?></strong>
                    <?php if (!empty($r['description'])): ?>
                      <br><small class="text-muted"><?= htmlspecialchars(mb_substr($r['description'], 0, 60)) ?>...</small>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($r['user'] ?? 'Unbekannt') ?></td>
                  <td><?= (int)($r['time_minutes'] ?? 0) ?></td>
                  <td><?= (int)($r['servings'] ?? 0) ?></td>
                  <td>
                    <?php foreach (array_slice($badges, 0, 3) as $b): ?>
                      <span class="badge me-1"><?= $b ?></span>
                    <?php endforeach; ?>
                    <?php if (count($badges) > 3): ?>
                      <small class="text-muted">+<?= count($badges) - 3 ?></small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="d-flex gap-2 flex-wrap">
                      <a href="recipe.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-sm">Ansehen</a>
                      <!-- Zentraler Delete: POST an recipe_delete.php -->
                      <form method="post" action="recipe_delete.php" class="d-inline">
                        <input type="hidden" name="id" value="<?= $id ?>">
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

