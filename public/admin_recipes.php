<?php
$pageTitle = 'Admin: Rezepte verwalten';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

/* === Zentrale Optionen + Filter-Logik laden === */
$TAG_OPTIONS = require __DIR__ . '/../includes/tag_options.php'; // nur hier definieren
require_once __DIR__ . '/../includes/filters.php';               // normalize + filter-Funktionen

/* === Dummy-Daten (bis DB da ist) === */
$recipes = [
  [
    'id'=>1, 'title'=>'Spaghetti Aglio e Olio', 'user'=>'anna',
    'meal'=>['Abendessen'], 'course'=>['Hauptgericht'], 'cuisine'=>['Italienisch'],
    'level'=>['Einfach'], 'specials'=>['Schnelle Küche','Vegetarisch'],
    'created'=>'2025-10-22'
  ],
  [
    'id'=>2, 'title'=>'Ramen mit Ei', 'user'=>'max',
    'meal'=>['Mittagessen'], 'course'=>['Hauptgericht'], 'cuisine'=>['Asiatisch'],
    'level'=>['Mittel'], 'specials'=>['Proteinreich'],
    'created'=>'2025-10-21'
  ],
  [
    'id'=>3, 'title'=>'Pancakes', 'user'=>'sara',
    'meal'=>['Frühstück','Dessert'], 'course'=>['Nachspeise'], 'cuisine'=>['Deutsch'],
    'level'=>['Einfach'], 'specials'=>['Vegetarisch'],
    'created'=>'2025-10-19'
  ],
];

/* === GET → normalisieren → filtern === */
$filters  = normalizeRecipeFilters($_GET);       // sorgt für Arrays bei meal/course/...
$filtered = filterRecipesArray($recipes, $filters);
?>

<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Admin · Rezepte verwalten</h1>
    <p class="text-muted">Übersicht aller Rezepte. Du kannst filtern oder löschen.</p>
  </section>

  <!-- FILTER: Collapsible mit Unter-Dropdowns -->
  <section class="section bg-cream mb-3 mb-md-4 py-3 px-3">
    <div class="d-flex justify-content-between align-items-center">
      <h2 class="h5 m-0">Filter</h2>
      <?php $filtersOpen = !empty($_GET); ?>
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
      <?php
        // Variablen, die das Include benötigt:
        $showUserFilter = true;   // Admin darf nach User filtern
        // $filters und $TAG_OPTIONS sind bereits gesetzt
        include __DIR__ . '/../includes/components/filter_recipes.php';
      ?>
    </div>
  </section>

  <!-- Tabelle -->
  <section class="section mb-3 mb-md-4">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>User</th>
            <th>Kategorien</th>
            <th>Datum</th>
            <th class="text-end">Aktionen</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($filtered)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">Keine Rezepte gefunden.</td></tr>
          <?php else: ?>
            <?php foreach ($filtered as $r): ?>
              <tr>
                <td><?= (int)$r['id'] ?></td>
                <td><?= htmlspecialchars($r['title']) ?></td>
                <td><?= htmlspecialchars($r['user']) ?></td>
                <td>
                  <?php foreach (array_merge($r['meal'],$r['course'],$r['cuisine'],$r['level'],$r['specials']) as $t): ?>
                    <span class="badge me-1 mb-1"><?= htmlspecialchars($t) ?></span>
                  <?php endforeach; ?>
                </td>
                <td><?= htmlspecialchars($r['created']) ?></td>
                <td class="text-end">
                  <a href="recipe.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-secondary">Ansehen</a>
                  <a href="admin_recipe_delete.php?id=<?= (int)$r['id'] ?>"
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Rezept wirklich löschen?');">Löschen</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

</div>

