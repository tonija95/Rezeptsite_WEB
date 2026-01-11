<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$isLoggedIn = isset($_SESSION['user_id'])
    && is_numeric($_SESSION['user_id'])
    && (int)$_SESSION['user_id'] > 0;

if ($isLoggedIn) {
    $role = isset($_SESSION['role']) ? (string)$_SESSION['role'] : 'user';
} else {
    $role = 'guest';
}
?>

<nav class="navbar navbar-expand-md navbar-dark site-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">Mein persönliches Kochbuch</a>

    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Menü öffnen">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">

      <ul class="navbar-nav mx-auto flex-column flex-md-row gap-3">
        <li class="nav-item">
          <a class="nav-link px-0 px-md-2" href="recipes.php">Rezepte</a>
        </li>


        <?php if ($role === 'user'): ?>
          <li class="nav-item">
            <a class="nav-link text-nowrap px-0 px-md-2" href="user_my_recipes.php">
              Meine Rezepte
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-0 px-md-2" href="user_favorites.php">
              Favoriten
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-0 px-md-2" href="user_shopping_list.php">
              Einkaufsliste
            </a>
          </li>
        <?php endif; ?>


        <?php if ($role === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link px-0 px-md-2" href="admin.php">Admin Panel</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-0 px-md-2" href="admin_users.php">User</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-0 px-md-2" href="admin_recipes.php">Rezepte verwalten</a>
          </li>
        <?php endif; ?>
      </ul>


      <div class="d-flex ms-md-3">
        <?php if ($role === 'guest'): ?>
          <div class="dropdown dropdown-login">
            <button class="btn btn-login dropdown-toggle" type="button"
                    id="loginDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
              Login
            </button>

            <div class="dropdown-menu dropdown-menu-end p-3"
                 aria-labelledby="loginDropdown" style="min-width:260px;">
              <form action="login_process.php" method="post">
                <div class="mb-3">
                  <label for="dropdownUsername" class="form-label">
                    Benutzername
                  </label>
                  <input type="text" class="form-control"
                         id="dropdownUsername" name="username"
                         required>
                </div>

                <div class="mb-3">
                  <label for="dropdownPassword" class="form-label">
                    Passwort
                  </label>
                  <input type="password" class="form-control"
                         id="dropdownPassword" name="password"
                         required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                  Anmelden
                </button>
              </form>

              <div class="dropdown-divider"></div>
              <a class="dropdown-item small" href="registration.php">
                Neu hier? Registrieren
              </a>
            </div>
          </div>
        <?php else: ?>
          <a class="btn btn-logout" href="logout.php">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>



<?php

if ($role === 'guest' && isset($_SESSION['login_error'])) {
    echo '<div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">'
            . htmlspecialchars($_SESSION['login_error']) .
            '<button type="button" class="btn-close" data-bs-dismiss="alert"
                     aria-label="Schließen"></button>
            </div>
          </div>';
    unset($_SESSION['login_error']);
}
?>
