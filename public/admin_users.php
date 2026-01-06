<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Admin-Check (neu: role, fallback: admin_logged_in)
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')
    || (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true);

if (!$isAdmin) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Admin: User verwalten';
$role = 'admin';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/helpers.php';

$users = getAllUsers();
$returnUrl = $_SERVER['REQUEST_URI'] ?? 'admin_users.php';
?>

<div class="container">

  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">User verwalten</h1>
    <p class="text-muted">Übersicht aller User im System. Du kannst User löschen.</p>
  </section>

  <section class="section mb-3 mb-md-4">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>E-Mail</th>
            <th>Rolle</th>
            <th class="text-end">Aktionen</th>
          </tr>
        </thead>

        <tbody>
          <?php if (empty($users)): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Keine Nutzer gefunden.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($users as $u): ?>
              <?php
                $uid  = (int)($u['id'] ?? 0);
                $name = (string)($u['name'] ?? '');
                $mail = (string)($u['email'] ?? '');
                $r    = (string)($u['role'] ?? 'user');
              ?>
              <tr>
                <td><?= esc((string)$uid) ?></td>
                <td><?= esc($name) ?></td>
                <td><?= esc($mail) ?></td>
                <td><?= esc($r) ?></td>
                <td class="text-end">
                  <form
                    method="post"
                    action="admin_user_delete.php"
                    class="d-inline"
                    onsubmit="return confirm('Diesen Nutzer wirklich löschen?');"
                  >
                    <input type="hidden" name="id" value="<?= esc((string)$uid) ?>">
                    <input type="hidden" name="return" value="<?= esc($returnUrl) ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Löschen</button>
                  </form>
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
