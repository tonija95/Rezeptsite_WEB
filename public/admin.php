<?php
$pageTitle = 'Admin: Dashboard';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

// Dummy-KPIs (später via DB)
$stats = [
  'total_users'       => 128,
  'total_recipes'     => 342,
  'new_users_7d'      => 6,
  'new_recipes_7d'    => 19,
  'flagged_recipes'   => 2,  // gemeldete/zu prüfen
];

// Dummy: letzte Rezepte
$recentRecipes = [
  ['id'=>351, 'title'=>'Tomatenrisotto', 'user'=>'anna', 'created'=>'2025-10-26', 'tags'=>['Italienisch','Hauptgericht']],
  ['id'=>350, 'title'=>'Schneller Couscous-Salat', 'user'=>'max', 'created'=>'2025-10-26', 'tags'=>['Schnelle Küche','Salat']],
  ['id'=>349, 'title'=>'Vegane Kürbissuppe', 'user'=>'sara', 'created'=>'2025-10-25', 'tags'=>['Vegan','Suppe']],
  ['id'=>348, 'title'=>'Apfelpfannkuchen', 'user'=>'lena', 'created'=>'2025-10-24', 'tags'=>['Frühstück','Dessert']],
  ['id'=>347, 'title'=>'Ramen mit Ei', 'user'=>'max', 'created'=>'2025-10-23', 'tags'=>['Asiatisch','Hauptgericht']],
];

// Dummy: neue Nutzer
$newUsers = [
  ['id'=>129, 'username'=>'lena', 'email'=>'lena@example.com', 'joined'=>'2025-10-26'],
  ['id'=>128, 'username'=>'flo',  'email'=>'flo@example.com',  'joined'=>'2025-10-25'],
  ['id'=>127, 'username'=>'timo', 'email'=>'timo@example.com', 'joined'=>'2025-10-24'],
];

// Dummy: gemeldete Rezepte (zu prüfen)
$flagged = [
  ['id'=>333, 'title'=>'XYZ Rezept', 'reason'=>'Unpassender Inhalt', 'reported_at'=>'2025-10-25'],
  ['id'=>326, 'title'=>'Seltsames Rezept', 'reason'=>'Spam-Verdacht',   'reported_at'=>'2025-10-22'],
];
?>
<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Admin · Dashboard</h1>
    <p class="text-muted">Schneller Überblick & wichtige Aktionen für die Verwaltung der Plattform.</p>
  </section>

  <!-- KPI-Karten -->
  <section class="section mb-3 mb-md-4">
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="text-muted small mb-1">Nutzer gesamt</div>
            <div class="h3 m-0"><?= (int)$stats['total_users'] ?></div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="text-muted small mb-1">Rezepte gesamt</div>
            <div class="h3 m-0"><?= (int)$stats['total_recipes'] ?></div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="text-muted small mb-1">Neue Nutzer (7 Tage)</div>
            <div class="h3 m-0"><?= (int)$stats['new_users_7d'] ?></div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <div class="card-body d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted small mb-1">Neue Rezepte (7 Tage)</div>
              <div class="h3 m-0"><?= (int)$stats['new_recipes_7d'] ?></div>
            </div>
            <?php if ($stats['flagged_recipes'] > 0): ?>
              <span class="badge rounded-pill" title="Gemeldete Rezepte">
                <?= (int)$stats['flagged_recipes'] ?> ⚠
              </span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 2-Spalten: Links letzte Rezepte, rechts neue Nutzer + gemeldete -->
  <section class="section bg-cream mb-3 mb-md-4">
    <div class="row g-3">
      <!-- Letzte Rezepte -->
      <div class="col-12 col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h2 class="h5 m-0">Letzte Rezepte</h2>
          <a href="admin_recipes.php" class="btn btn-sm btn-outline-secondary">Alle verwalten</a>
        </div>

        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Titel</th>
                <th class="d-none d-sm-table-cell">User</th>
                <th class="d-none d-md-table-cell">Tags</th>
                <th>Datum</th>
                <th class="text-end">Aktionen</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentRecipes as $r): ?>
                <tr>
                  <td class="fw-semibold"><?= htmlspecialchars($r['title']) ?></td>
                  <td class="d-none d-sm-table-cell"><?= htmlspecialchars($r['user']) ?></td>
                  <td class="d-none d-md-table-cell">
                    <?php foreach (($r['tags'] ?? []) as $t): ?>
                      <span class="badge me-1 mb-1"><?= htmlspecialchars($t) ?></span>
                    <?php endforeach; ?>
                  </td>
                  <td class="text-muted small"><?= htmlspecialchars($r['created']) ?></td>
                  <td class="text-end">
                    <a href="recipe.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-secondary">Ansehen</a>
                    <a href="admin_recipe_delete.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Rezept wirklich löschen?');">Löschen</a>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($recentRecipes)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Keine Einträge.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Rechte Spalte: Neue Nutzer & Gemeldete -->
      <div class="col-12 col-lg-4">
        <!-- Neue Nutzer -->
        <div class="section mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h6 m-0">Neue Nutzer</h2>
            <a href="admin_users.php" class="btn btn-sm btn-outline-secondary">Alle</a>
          </div>
          <ul class="list-group list-group-flush">
            <?php foreach ($newUsers as $u): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-semibold"><?= htmlspecialchars($u['username']) ?></div>
                  <div class="small text-muted"><?= htmlspecialchars($u['email']) ?></div>
                </div>
                <span class="small text-muted"><?= htmlspecialchars($u['joined']) ?></span>
              </li>
            <?php endforeach; ?>
            <?php if (empty($newUsers)): ?>
              <li class="list-group-item text-muted">Keine neuen Nutzer.</li>
            <?php endif; ?>
          </ul>
        </div>



    </div>
  </section>

  <!-- Schnellaktionen -->
  <section class="section mb-5">
    <h2 class="h5 mb-3">Schnellaktionen</h2>
    <div class="d-flex flex-wrap gap-2">
      <a href="admin_recipes.php" class="btn btn-primary">Rezepte verwalten</a>
      <a href="admin_users.php" class="btn btn-outline-secondary">User verwalten</a>
      <a href="recipes.php" class="btn btn-outline-secondary">Öffentliche Rezeptliste</a>
    </div>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
