<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <title>Theme-Test · Rezeptsite</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS (nur CSS, kein JS) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Dein Theme -->
  <link rel="stylesheet" href="css/teststyle.css">
</head>
<body id="top">

  <!-- NAVBAR -->
  <nav class="navx">
    <input id="navx-toggle" class="navx__toggle" type="checkbox" aria-hidden="true">
    <div class="navx__bar container">
      <a class="navx__brand" href="#">Rezeptsite</a>
      <label for="navx-toggle" class="navx__btn" aria-label="Menü öffnen/schließen" aria-controls="navx-menu" aria-expanded="false">
        <span class="navx__icon" aria-hidden="true"></span>
      </label>
    </div>
    <ul id="navx-menu" class="navx__menu container">
      <li><a href="#">Home</a></li>
      <li><a href="#">Rezepte</a></li>
      <li><a href="#">Login</a></li>
      <li><a href="#">Dashboard</a></li>
      <li><a href="#">Favoriten</a></li>
      <li><a href="#">Einkaufsliste</a></li>
      <li><a href="#">Admin</a></li>
    </ul>
  </nav>

  <main class="container container-narrow my-4">
    <!-- HERO -->
    <section class="hero section mb-4">
      <h1 class="h3 mb-2">Hero-Test</h1>
      <p class="text-muted">Cremiger Verlauf, Buttons zeigen Primär-/Sekundärfarben.</p>
      <div class="d-flex gap-2">
        <a class="btn btn-primary btn-sm" href="#">Primär-Button</a>
        <a class="btn btn-outline-secondary btn-sm" href="#">Outline-Button</a>
      </div>
    </section>

    <!-- CREME-SEKTION -->
    <section class="section bg-cream mb-4">
      <h2 class="h5 mb-2">BG-Cream Test</h2>
      <p class="mb-0">Sektion mit hellem Greige-Hintergrund.</p>
    </section>

    <!-- CARDS -->
    <section class="mb-4">
      <h2 class="h5 mb-3">Cards (Rezeptkarten) – Test</h2>
      <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-4">
          <article class="card recipe-card">
            <img class="card-img-top" src="https://picsum.photos/640/400?random=11" alt="">
            <div class="card-body">
              <span class="badge me-1">Pasta</span>
              <span class="badge me-1">Schnell</span>
              <h3 class="card-title h5 mt-2">Beispielrezept A</h3>
              <p class="card-text text-muted">Kurze Beschreibung …</p>
              <a class="btn btn-outline-secondary btn-sm" href="#">Ansehen</a>
            </div>
          </article>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
          <article class="card recipe-card">
            <img class="card-img-top" src="https://picsum.photos/640/400?random=12" alt="">
            <div class="card-body">
              <span class="badge me-1">Vegan</span>
              <h3 class="card-title h5 mt-2">Beispielrezept B</h3>
              <p class="card-text text-muted">Noch eine Kurzbeschreibung …</p>
              <a class="btn btn-outline-secondary btn-sm" href="#">Ansehen</a>
            </div>
          </article>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
          <article class="card recipe-card">
            <img class="card-img-top" src="https://picsum.photos/640/400?random=13" alt="">
            <div class="card-body">
              <span class="badge me-1">Dessert</span>
              <h3 class="card-title h5 mt-2">Beispielrezept C</h3>
              <p class="card-text text-muted">Kurzer Text …</p>
              <a class="btn btn-outline-secondary btn-sm" href="#">Ansehen</a>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- FORMULARE -->
    <section class="section mb-4">
      <h2 class="h5 mb-3">Formulare – Test</h2>
      <form class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Rezeptname</label>
          <input class="form-control" placeholder="z. B. Spaghetti Aglio e Olio">
        </div>
        <div class="col-md-6">
          <label class="form-label">Kategorie</label>
          <select class="form-select">
            <option>Vorspeise</option>
            <option>Hauptgericht</option>
            <option>Dessert</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Beschreibung</label>
          <textarea class="form-control" rows="3"></textarea>
        </div>
        <div class="col-12">
          <button class="btn btn-primary">Speichern</button>
        </div>
      </form>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="footer mt-5 py-4">
    <div class="container container-narrow d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <div>© 2025 Rezeptsite</div>
      <div><a href="#top">↑ Zurück nach oben</a></div>
    </div>
  </footer>

  <a class="back-to-top" href="#top" aria-label="Zurück nach oben">↑</a>
</body>
</html>
