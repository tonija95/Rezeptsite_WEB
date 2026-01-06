<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nur Admins erlauben
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Admin Panel';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>

<div class="container">

  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Admin Panel</h1>
    <p class="text-muted mb-0">
      Zentrale Verwaltung für Nutzer und Rezepte.
    </p>
  </section>

  <section class="section bg-cream mb-3 mb-md-4 py-4 px-3">
    <div class="row g-3">

      <!-- User verwalten -->
      <div class="col-12 col-md-6">
        <div class="card h-100">
          <div class="card-body d-flex flex-column">
            <h2 class="h5 mb-2">👤 User verwalten</h2>
            <p class="text-muted flex-grow-1">
              Übersicht aller Nutzer und Möglichkeit, User zu löschen.
            </p>
            <a href="admin_users.php" class="btn btn-outline-secondary mt-auto">
              Zur Userverwaltung
            </a>
          </div>
        </div>
      </div>

      <!-- Rezepte verwalten -->
      <div class="col-12 col-md-6">
        <div class="card h-100">
          <div class="card-body d-flex flex-column">
            <h2 class="h5 mb-2">🍽 Rezepte verwalten</h2>
            <p class="text-muted flex-grow-1">
              Alle Rezepte im System ansehen und bei Bedarf löschen.
            </p>
            <a href="admin_recipes.php" class="btn btn-outline-secondary mt-auto">
              Zur Rezeptverwaltung
            </a>
          </div>
        </div>
      </div>

    </div>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
