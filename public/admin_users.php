<?php
$pageTitle = 'Admin: User verwalten';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

/* Dummy-Userdaten */
$users = [
  ['id'=>1, 'username'=>'anna', 'email'=>'anna@example.com', 'role'=>'user', 'joined'=>'2025-10-01'],
  ['id'=>2, 'username'=>'max',  'email'=>'max@example.com',  'role'=>'user', 'joined'=>'2025-10-05'],
  ['id'=>3, 'username'=>'admin','email'=>'admin@example.com','role'=>'admin','joined'=>'2025-09-28'],
];

/* Filter übernehmen */
$filters = [
  'username' => isset($_GET['username']) ? trim($_GET['username']) : '',
  'email'    => isset($_GET['email'])    ? trim($_GET['email'])    : '',
];

/* Filtern */
$filtered = array_filter($users, function($u) use ($filters) {
  if ($filters['username'] && stripos($u['username'], $filters['username']) === false) return false;
  if ($filters['email']    && stripos($u['email'],    $filters['email'])    === false) return false;
  return true;
});

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Admin · User verwalten</h1>
    <p class="text-muted">Hier kannst du Nutzer suchen und löschen. Bearbeitungen sind nicht möglich.</p>
  </section>

  <!-- Filter -->
  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <h2 class="h5 mb-3">Filter</h2>
    <form method="get" class="row g-3 align-items-end">
      <div class="col-12 col-md-5 col-lg-4">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username"
               value="<?= h($filters['username']) ?>" placeholder="z. B. anna">
      </div>
      <div class="col-12 col-md-5 col-lg-4">
        <label class="form-label">E-Mail</label>
        <input type="text" class="form-control" name="email"
               value="<?= h($filters['email']) ?>" placeholder="name@example.com">
      </div>
      <div class="col-12 col-md-2 col-lg-2 d-flex gap-2">
        <button class="btn btn-primary w-100">Filtern</button>
        <a href="admin_users.php" class="btn btn-outline-secondary w-100">Zurücksetzen</a>
      </div>
    </form>
  </section>

  <!-- Tabelle -->
  <section class="section mb-3 mb-md-4">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>E-Mail</th>
            <th>Rolle</th>
            <th>Beigetreten</th>
            <th class="text-end">Aktionen</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($filtered)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">Keine Nutzer gefunden.</td></tr>
          <?php else: ?>
            <?php foreach ($filtered as $u): ?>
              <tr>
                <td><?= (int)$u['id'] ?></td>
                <td><?= h($u['username']) ?></td>
                <td><?= h($u['email']) ?></td>
                <td><?= h($u['role']) ?></td>
                <td class="text-muted small"><?= h($u['joined']) ?></td>
                <td class="text-end">
                  <a href="admin_user_delete.php?id=<?= (int)$u['id'] ?>"
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Diesen Nutzer wirklich löschen?');">
                    Löschen
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

