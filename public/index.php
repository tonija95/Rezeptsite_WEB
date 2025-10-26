<?php
$pageTitle = 'Homepage';
// zum Testen kannst du hier 'guest' | 'user' | 'admin' setzen:
$role = 'guest';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>
<div class="container">
  <section class="bg-white border rounded p-4 p-md-5 mb-4">
    <h1 class="h3 h2-md">Willkommen im persönlichen Kochbuch</h1>
    <p class="text-muted mb-0">
      Lege eigene Rezepte an, bearbeite sie, lade Bilder hoch und organisiere Favoriten & Einkaufsliste.
    </p>
  </section>

  <h2 class="h4 mb-3">Neueste Rezepte</h2>
  <div class="row g-3">
    <?php for ($i=1;$i<=6;$i++): ?>
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card h-100">
          <img src="img/placeholder.jpg" class="card-img-top" alt="">
          <div class="card-body">
            <h3 class="card-title h5 mb-2">Beispielrezept <?= $i ?></h3>
            <p class="card-text text-muted">Kurze Beschreibung …</p>
            <a href="recipe.php" class="btn btn-primary btn-sm">Ansehen</a>
          </div>
        </div>
      </div>
    <?php endfor; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
