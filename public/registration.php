<?php
$pageTitle = 'Registrierung / Login';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

?>
<main>
<div class="container">

  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Konto erstellen oder anmelden</h1>
    <p class="text-muted mb-0">
      Registriere dich, um Rezepte zu speichern, Favoriten zu verwalten und deine Einkaufsliste zu nutzen.
    </p>
  </section>

  <div class="row g-3">

    <div class="col-12 col-lg-6">
      <section class="bg-cream section mb-3 mb-md-4">
        <h2 class="fs-5 mb-3">Kostenlos registrieren</h2>

        <form action="register_process.php" method="post" class="row g-3">

          <div class="col-12">
            <label for="regUsername" class="form-label">Benutzername</label>
            <input
              type="text"
              class="form-control"
              id="regUsername"
              name="username"
              placeholder="z. B. kochfan84"
              minlength="3"
              maxlength="30"
              pattern="[A-Za-z0-9_]{3,30}"
              autocomplete="username"
              required
            >
            <div class="form-text">3–30 Zeichen, nur Buchstaben, Zahlen und Unterstrich.</div>
          </div>

          <div class="col-12">
            <label for="regEmail" class="form-label">E-Mail-Adresse</label>
            <input
              type="email"
              class="form-control"
              id="regEmail"
              name="email"
              placeholder="email@example.com"
              autocomplete="email"
              required
            >
          </div>

          <div class="col-12">
            <label for="regPassword" class="form-label">Passwort</label>
            <input
              type="password"
              class="form-control"
              id="regPassword"
              name="password"
              minlength="8"
              autocomplete="new-password"
              required
            >
            <div class="form-text">Mindestens 8 Zeichen.</div>
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary">Registrieren</button>
          </div>

        </form>
      </section>
    </div>

    <div class="col-12 col-lg-6">
      <section class="bg-cream section mb-3 mb-md-4">
        <h2 class="fs-5 mb-3">Bereits registriert?</h2>
        <p class="text-muted">Melde dich an, um deine Rezepte zu verwalten.</p>

        <form action="login_process.php" method="post" class="row g-3">

          <div class="col-12">
            <label for="loginUsername" class="form-label">Benutzername</label>
            <input
              type="text"
              class="form-control"
              id="loginUsername"
              name="username"
              placeholder="Benutzername"
              autocomplete="username"
              required
            >
          </div>

          <div class="col-12">
            <label for="loginPassword" class="form-label">Passwort</label>
            <input
              type="password"
              class="form-control"
              id="loginPassword"
              name="password"
              autocomplete="current-password"
              required
            >
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary">Anmelden</button>
          </div>

        </form>
      </section>
    </div>

  </div>

</div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
