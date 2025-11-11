<?php
$pageTitle = 'Dein Dashboard';
$role = 'user';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

/* ===== Dummy-Daten (später per DB ersetzen) ===== */
$currentUser = [
  'username' => 'anna',
  'email'    => 'anna@example.com'
];

$myRecipes = [
  ['id'=>101,'title'=>'Tomatenrisotto','created'=>'2025-10-26','tags'=>['Italienisch','Hauptgericht']],
  ['id'=>99, 'title'=>'Vegane Kürbissuppe','created'=>'2025-10-24','tags'=>['Vegan','Suppe']],
  ['id'=>95, 'title'=>'Apfelpfannkuchen','created'=>'2025-10-20','tags'=>['Frühstück','Dessert']],
];

$favorites = [351, 330, 327, 299];
$shoppingListItems = [
  ['name'=>'Spaghetti','qty'=>'500','unit'=>'g'],
  ['name'=>'Tomaten','qty'=>'4','unit'=>'Stk'],
  ['name'=>'Olivenöl','qty'=>'','unit'=>'EL'],
];

$stats = [
  'my_total'      => count($myRecipes),
  'my_recent'     => min(count($myRecipes), 5),
  'my_favorites'  => count($favorites),
  'shopping_cnt'  => count($shoppingListItems),
];
?>
<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Hallo, <?= htmlspecialchars($currentUser['username']) ?>!</h1>
    <p class="text-muted">Schneller Überblick, Aktionen und deine neuesten Rezepte.</p>
  </section>

  <!-- Schnellaktionen (nach oben verlegt) -->
  <section class="section mb-3 mb-md-4">
    <h2 class="h6 mb-3">Schnellaktionen</h2>
    <div class="d-flex flex-wrap gap-2">
      <a href="user_recipe_edit.php" class="btn btn-primary">Neues Rezept erstellen</a>
      <a href="user_my_recipes.php" class="btn btn-outline-secondary">Meine Rezepte</a>
      <a href="user_favorites.php" class="btn btn-outline-secondary">Favoriten</a>
      <a href="user_shopping_list.php" class="btn btn-outline-secondary">Einkaufsliste</a>

      <!-- Passwort ändern: Toggle -->
      <button class="btn btn-outline-secondary"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#collapseChangePw"
              aria-expanded="false"
              aria-controls="collapseChangePw">
        Passwort ändern
      </button>
    </div>

    <!-- Passwort ändern: Inhalt -->
    <div id="collapseChangePw" class="collapse mt-3">
      <div class="bg-cream section">
        <form action="user_password_change.php" method="post" class="row g-3">
          <div class="col-12 col-md-4">
            <label for="oldPassword" class="form-label">Altes Passwort</label>
            <input type="password" class="form-control" id="oldPassword" name="old_password" required>
          </div>
          <div class="col-12 col-md-4">
            <label for="newPassword" class="form-label">Neues Passwort</label>
            <input type="password" class="form-control" id="newPassword" name="new_password" required minlength="8">
          </div>
          <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Speichern</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#collapseChangePw">Abbrechen</button>
          </div>
          <div class="col-12">
            <div class="form-text">
              Tipp: Mindestens 8 Zeichen, eine Zahl und ein Sonderzeichen sind sinnvoll.
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- KPIs -->
  <section class="section mb-3 mb-md-4">
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="small text-muted mb-1">Meine Rezepte</div>
            <div class="h3 m-0"><?= (int)$stats['my_total'] ?></div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="small text-muted mb-1">Favoriten</div>
            <div class="h3 m-0"><?= (int)$stats['my_favorites'] ?></div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="small text-muted mb-1">Einkaufsliste</div>
            <div class="h3 m-0"><?= (int)$stats['shopping_cnt'] ?></div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="small text-muted mb-1">Letzte Einträge</div>
            <div class="h3 m-0"><?= (int)$stats['my_recent'] ?></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 2-Spalten Bereich -->
  <section class="section bg-cream mb-5">
    <div class="row g-3">

      <!-- Links: Neueste eigene Rezepte -->
      <div class="col-12 col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h2 class="h5 m-0">Deine neuesten Rezepte</h2>
          <a href="user_my_recipes.php" class="btn btn-sm btn-outline-secondary">Alle ansehen</a>
        </div>

        <?php if (empty($myRecipes)): ?>
          <div class="text-muted">Du hast noch keine Rezepte hinzugefügt.</div>
        <?php else: ?>
          <div class="row g-3">
            <?php foreach ($myRecipes as $r): ?>
              <div class="col-12 col-md-6">
                <div class="card h-100">
                  <img src="https://picsum.photos/400/300?random=<?= (int)$r['id'] ?>"
                       onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
                       class="card-img-top" alt="Rezeptbild">
                  <div class="card-body">
                    <?php if (!empty($r['tags'])): ?>
                      <div class="mb-2">
                        <?php foreach ($r['tags'] as $t): ?>
                          <span class="badge me-1 mb-1"><?= htmlspecialchars($t) ?></span>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                    <h3 class="card-title h5 mb-1"><?= htmlspecialchars($r['title']) ?></h3>
                    <p class="card-text text-muted small mb-3">Erstellt am <?= htmlspecialchars($r['created']) ?></p>
                    <div class="d-flex gap-2">
                      <a class="btn btn-outline-secondary btn-sm" href="recipe.php?id=<?= (int)$r['id'] ?>">Ansehen</a>
                      <a class="btn btn-primary btn-sm" href="user_recipe_edit.php?id=<?= (int)$r['id'] ?>">Bearbeiten</a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Rechts: Favoriten & Einkaufsliste -->
      <div class="col-12 col-lg-4">
        <div class="section mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h6 m-0">Favoriten (Kurzliste)</h2>
            <a href="user_favorites.php" class="btn btn-sm btn-outline-secondary">Alle</a>
          </div>
          <?php if (empty($favorites)): ?>
            <div class="text-muted small">Noch keine Favoriten.</div>
          <?php else: ?>
            <ul class="list-group list-group-flush">
              <?php foreach (array_slice($favorites, 0, 5) as $fid): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span>Rezept #<?= (int)$fid ?></span>
                  <a class="btn btn-sm btn-outline-secondary" href="recipe.php?id=<?= (int)$fid ?>">Ansehen</a>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>

        <div class="section">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h6 m-0">Einkaufsliste (Kurz)</h2>
            <a href="user_shopping_list.php" class="btn btn-sm btn-outline-secondary">Zur Liste</a>
          </div>
          <?php if (empty($shoppingListItems)): ?>
            <div class="text-muted small">Keine Einträge.</div>
          <?php else: ?>
            <ul class="list-group list-group-flush">
              <?php foreach (array_slice($shoppingListItems, 0, 5) as $it): ?>
                <li class="list-group-item d-flex justify-content-between">
                  <span><?= htmlspecialchars($it['name']) ?></span>
                  <span class="text-muted small">
                    <?= htmlspecialchars(trim(($it['qty'] ?? '').' '.($it['unit'] ?? ''))) ?>
                  </span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
