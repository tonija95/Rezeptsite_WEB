<?php
// TEMP für frühe Phase: Rolle manuell setzen, z.B. 'guest' | 'user' | 'admin'.
// Später durch Session ersetzen (z.B. $_SESSION['user']['role']).
$role = $role ?? 'guest';
?>
<header class="bg-white border-bottom mb-4">
  <div class="container py-3 d-flex flex-column flex-md-row align-items-md-center gap-3">
    <a class="navbar-brand fw-semibold me-md-auto" href="index.php">Rezeptsite</a>

    <!-- Mobile-first: vertikale Liste auf Phones, ab md horizontal -->
    <ul class="nav flex-column flex-md-row gap-2">
      <li class="nav-item"><a class="nav-link px-0 px-md-2" href="recipes.php">Rezepte</a></li>

      <?php if ($role === 'guest'): ?>
        <li class="nav-item"><a class="nav-link px-0 px-md-2" href="login.php">Login/Registrierung</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link px-0 px-md-2" href="user_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link px-0 px-md-2" href="user_my_recipes.php">Meine Rezepte</a></li>
        <li class="nav-item"><a class="nav-link px-0 px-md-2" href="user_favorites.php">Favoriten</a></li>
        <li class="nav-item"><a class="nav-link px-0 px-md-2" href="user_shopping_list.php">Einkaufsliste</a></li>
        <li class="nav-item"><a class="nav-link px-0 px-md-2 text-danger" href="logout.php">Logout</a></li>
      <?php endif; ?>

      <?php if ($role === 'admin'): ?>
        <li class="nav-item"><a class="nav-link px-0 px-md-2" href="admin.php">Admin Panel</a></li>
        <li class="nav-item"><a class="nav-link px-0 px-md-2" href="admin_users.php">Admin: User</a></li>
        <li class="nav-item"><a class="nav-link px-0 px-md-2" href="admin_recipes.php">Admin: Rezepte</a></li>
      <?php endif; ?>
    </ul>
  </div>
</header>
