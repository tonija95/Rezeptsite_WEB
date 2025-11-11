<?php
$pageTitle = 'Rezept';
// zum Testen kannst du hier 'guest' | 'user' | 'admin' setzen:
$role = 'admin';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>
<div class="container">

  <div class="row justify-content-center g-4">
    <article class="col-12 col-md-8">

      <!-- Titel -->
      <section class="section hero my-3 my-md-4">
        <h1 class="fs-3 mb-2">Spaghetti Aglio e Olio</h1>
        <p class="mb-3 text-muted">
          Kurzbeschreibung: Ein ultra simples Pastarezept mit Knoblauch, Chili und Olivenöl.
        </p>

        <div class="d-flex flex-wrap gap-3 small text-muted">
          <span>👩‍🍳 von <strong>Anna Muster</strong></span>
          <span>⏱ Zubereitung: 20 min</span>
          <span>👥 2 Portionen</span>
          <span>★ ★ ★ ★ ☆ (12)</span>
        </div>

        <!-- Tags (aus deinem Set: Abendessen · Italienisch · Einfach) -->
        <div class="d-flex flex-wrap gap-2 my-3">
          <span class="badge rounded-pill" style="background:var(--color-accent);color:var(--color-dark);">Abendessen</span>
          <span class="badge rounded-pill" style="background:var(--color-accent);color:var(--color-dark);">Italienisch</span>
          <span class="badge rounded-pill" style="background:var(--color-accent);color:var(--color-dark);">Einfach</span>
        </div>

        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-primary btn-sm" href="shoppinglist_add.php?id=42">＋ Einkaufsliste</a>
          <a class="btn btn-outline-secondary btn-sm" href="favorite_toggle.php?id=42">★ Favorit</a>
        </div>
      </section>

      <!-- Bild + Zutaten -->
      <section class="section bg-cream mb-3 mb-md-4">
        <div class="row g-3 align-items-start">
          <div class="col-12 col-lg-6">
            <figure class="m-0">
              <picture>
                <img
                  src="<?= $recipe['image_url'] ?? 'https://picsum.photos/600/400?random=21' ?>"
                  onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
                  alt="<?= htmlspecialchars($recipe['title'] ?? 'Rezeptbild') ?>"
                  class="img-fluid rounded recipe-img">
              </picture>
              <figcaption class="text-muted small mt-1">Frisch, schnell, klassisch italienisch.</figcaption>
            </figure>
          </div>

          <!-- Zutaten -->
          <div class="col-12 col-lg-6">
            <h2 class="fs-5 mb-3">Zutaten</h2>
            <ul class="mb-0">
              <li>200 g Spaghetti</li>
              <li>2–3 Knoblauchzehen</li>
              <li>1 rote Chili</li>
              <li>Olivenöl, Salz, Pfeffer</li>
              <li>Optional: Petersilie</li>
            </ul>
          </div>
        </div>
      </section>

      <!-- Schritte -->
      <section class="section bg-cream mb-3 mb-md-4">
        <div class="col-12 col-lg-6">
          <h2 class="fs-5 mb-3">Zubereitung</h2>
          <ol class="mb-0">
            <li>Spaghetti in Salzwasser kochen.</li>
            <li>Knoblauch & Chili in Olivenöl bei mittlerer Hitze anschwitzen.</li>
            <li>Spaghetti abgießen, mit dem Öl mischen, würzen.</li>
            <li>Mit Petersilie servieren.</li>
          </ol>
        </div>
      </section>
    </article>

    <!-- Ähnliche Rezepte -->
    <aside class="col-12 col-lg-4">
      <section class="section mt-4" style="top:5rem;">
        <h2 class="fs-6 mb-4">Ähnliche Rezepte</h2> <!-- FIX: h2 korrekt geschlossen -->

        <div class="card h-100 mb-4">
          <img
            src="https://picsum.photos/400/300?random=31"
            onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
            alt="Rezeptbild"
            class="card-img-top">
          <div class="card-body">
            <span class="badge me-1">Italienisch</span>
            <span class="badge me-1">Einfach</span>
            <h3 class="card-title h5 mb-2">Beispielrezept</h3>
            <p class="card-text text-muted">Kurze Beschreibung …</p>
            <a href="recipe.php?id=101" class="btn btn-outline-secondary">Ansehen</a>
          </div>
        </div>

        <div class="card h-100 mb-4">
          <img
            src="https://picsum.photos/400/300?random=32"
            onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
            alt="Rezeptbild"
            class="card-img-top">
          <div class="card-body">
            <span class="badge me-1">Abendessen</span>
            <span class="badge me-1">Schnelle Küche</span>
            <h3 class="card-title h5 mb-2">Beispielrezept</h3>
            <p class="card-text text-muted">Kurze Beschreibung …</p>
            <a href="recipe.php?id=102" class="btn btn-outline-secondary">Ansehen</a>
          </div>
        </div>

        <div class="card h-100 mb-4">
          <img
            src="https://picsum.photos/400/300?random=33"
            onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
            alt="Rezeptbild"
            class="card-img-top">
          <div class="card-body">
            <span class="badge me-1">Vegetarisch</span>
            <span class="badge me-1">Mediterran</span>
            <h3 class="card-title h5 mb-2">Beispielrezept</h3>
            <p class="card-text text-muted">Kurze Beschreibung …</p>
            <a href="recipe.php?id=103" class="btn btn-outline-secondary">Ansehen</a>
          </div>
        </div>

      </section>
    </aside>
  </div>

</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
