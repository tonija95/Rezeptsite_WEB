<!-- Registrierung -->
 <?php
$pageTitle = 'Rezepte';
// zum Testen kannst du hier 'guest' | 'user' | 'admin' setzen:
$role = 'admin';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>
<div class="container">
<!-- Registrierung -->
<section class="hero section my-3 my-md-4">
  <h1 class="h3 mb-2">Registriere dich und starte dein Kochbuch</h1>
  <p class="text-muted">
    Lege ein Konto an, speichere eigene Rezepte und organisiere Favoriten & Einkaufsliste.
  </p>
</section>

<section class="bg-cream section mb-3 mb-md-4">
  <h2 class="fs-5 mb-3">Kostenlos registrieren</h2>

  <form action="register_process.php" method="post" class="row g-3">
    <!-- Benutzername -->
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

    <!-- E-Mail -->
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

    <!-- Passwort -->
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

    <!-- Absenden -->
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Registrieren</button>
    </div>
  </form>
</section>


<!-- Login für bestehende Nutzer -->
<section class="section bg-cream mb-3 mb-md-4">
  <h2 class="fs-5 mb-3">Bereits registriert?</h2>
  <p class="text-muted">Melde dich an, um deine Rezepte zu verwalten und neue Ideen zu entdecken.</p>

  <form action="login_process.php" method="post" class="row g-3">
    <!-- E-Mail -->
    <div class="col-12">
      <label for="loginEmail" class="form-label">E-Mail-Adresse</label>
      <input
        type="email"
        class="form-control"
        id="loginEmail"
        name="email"
        placeholder="email@example.com"
        autocomplete="email"
        required
      >
    </div>

    <!-- Passwort -->
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


    <!-- Absenden -->
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Anmelden</button>
    </div>


  </form>
</section>

</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>