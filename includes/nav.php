<?php
// TEMP: Rolle mocken – später: $_SESSION['user']['role'] ?? 'guest'
$role = $role ?? 'admin';
?>
<nav class="navbar navbar-expand-md navbar-dark site-navbar sticky-top"> <!-- navbar-dark bg-dark -->
  <div class="container">
    <a class="navbar-brand" href="index.php">Mein persönliches Kochbuch</a>
   

    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Menü öffnen">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <!-- Mitte: Hauptlinks -->
      <ul class="navbar-nav mx-auto flex-column flex-md-row gap-3">
        <li class="nav-item">
          <a class="nav-link px-0 px-md-2" href="recipes.php">Rezepte</a>
        </li>

        <?php if ($role !== 'guest'): ?>
          <li class="nav-item">
            <a class="nav-link px-0 px-md-2" href="user_dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-nowrap px-0 px-md-2" href="user_my_recipes.php">Meine Rezepte</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-0 px-md-2" href="user_favorites.php">Favoriten</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-0 px-md-2" href="user_shopping_list.php">Einkaufsliste</a>
          </li>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle px-0 px-md-2"
               href="#" id="adminMenu" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              Admin
            </a>
            <ul class="dropdown-menu" aria-labelledby="adminMenu">
              <li><a class="dropdown-item" href="admin.php">Admin Panel</a></li>
              <li><a class="dropdown-item" href="admin_users.php">User</a></li>
              <li><a class="dropdown-item" href="admin_recipes.php">Rezepte verwalten</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>

      <!-- Rechts: Login/Logout als Button -->
      <div class="d-flex ms-md-3">
        <?php if ($role === 'guest'): ?>
<div class="dropdown dropdown-login">

  <button class="btn btn-login dropdown-toggle" type="button" id="loginDropdown"
          data-bs-toggle="dropdown" aria-expanded="false">
    Login
  </button>


  <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="loginDropdown" style="min-width: 260px;">
    <form action="login_process.php" method="post">
      <div class="mb-3">
        <label for="dropdownEmail" class="form-label">E-Mail-Adresse</label>
        <input type="email" class="form-control" id="dropdownEmail" name="email"
               placeholder="email@example.com" required>
      </div>

      <div class="mb-3">
        <label for="dropdownPassword" class="form-label">Passwort</label>
        <input type="password" class="form-control" id="dropdownPassword" name="password"
               placeholder="••••••••" required>
      </div>


      <button type="submit" class="btn btn-primary w-100">Anmelden</button>
    </form>

    <div class="dropdown-divider"></div>
    <a class="dropdown-item small" href="registration.php">Neu hier? Registrieren</a>
  </div>
</div>
        <?php else: ?>
          <a class="btn btn-logout" href="logout.php">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main>
