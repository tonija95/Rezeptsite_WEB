<?php
$pageTitle = 'Rezepte verwalten (Admin)';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/pre datatable/get_options.php';
require_once __DIR__ . '/../includes/pre datatable/recipe_examples.php';
require_once __DIR__ . '/../includes/filters.php';

$allRecipes  = getExampleRecipes();
$tagFilters  = normalizeTagFilters($_GET);
$filtered    = filterRecipesByTags($allRecipes, $tagFilters);
$filtersOpen = !empty($_GET);
?>
<div class="container">

  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Rezepte verwalten</h1>
    <p class="text-muted">Übersicht aller Rezepte im System – bearbeiten, löschen oder Details ansehen.</p>
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
                    if ($v !== '') $badges[] = esc($v);
                  }
                } else {
                  $v = trim((string)$vals);
                  if ($v !== '') $badges[] = esc($v);
                }
              }
            }
          ?>
          <div class="card mb-3">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <h3 class="h6 mb-1"><?= esc($r['title'] ?? 'Unbenannt') ?></h3>
                  <small class="text-muted">@<?= esc($r['user'] ?? 'Unbekannt') ?></small>
                </div>
              </div>
              <div class="d-flex gap-2 mt-3">
                <a href="recipe.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-sm flex-fill">Ansehen</a>
                <a href="admin_recipe_delete.php?id=<?= $id ?>" class="btn btn-danger btn-sm flex-fill">Löschen</a>
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
                          if ($v !== '') $badges[] = esc($v);
                        }
                      } else {
                        $v = trim((string)$vals);
                        if ($v !== '') $badges[] = esc($v);
                      }
                    }
                  }
                ?>
                <tr>
                  <td>
                    <strong><?= esc($r['title'] ?? 'Unbenannt') ?></strong>
                    <?php if (!empty($r['description'])): ?>
                      <br><small class="text-muted"><?= esc(mb_substr($r['description'], 0, 60)) ?>...</small>
                    <?php endif; ?>
                  </td>
                  <td><?= esc($r['user'] ?? 'Unbekannt') ?></td>
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
                      <a href="admin_recipe_delete.php?id=<?= $id ?>" class="btn btn-danger btn-sm">Löschen</a>
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

