<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || (int)$_SESSION['user_id'] <= 0) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Meine Einkaufsliste';
$role = 'user';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/db_gets.php';
require_once __DIR__ . '/../includes/db_inserts.php';

$userId = (int)$_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['delete_id']) && ctype_digit($_POST['delete_id'])) {
        deleteShoppingListItem((int)$_POST['delete_id'], $userId);
    }

    if (isset($_POST['clear_all'])) {
        clearShoppingListByUserId($userId);
    }

    header('Location: user_shopping_list.php');
    exit;
}

$shoppingItems = getShoppingListByUserId($userId);
?>

<main>
<div class="container">

    <section class="hero section my-3 my-md-4">
        <h1 class="h3 mb-2">Einkaufsliste</h1>
        <p class="text-muted">Hier kannst du deine benötigten Zutaten verwalten.</p>
    </section>

    <section class="section bg-cream mb-5">

        <?php if (!empty($shoppingItems)): ?>

            <div class="d-flex justify-content-end mb-2">
                <form method="post" onsubmit="return confirm('Einkaufsliste wirklich komplett leeren?');">
                    <button
                        type="submit"
                        name="clear_all"
                        value="1"
                        class="btn btn-sm btn-outline-danger"
                    >
                        Liste leeren
                    </button>
                </form>
            </div>

            <ul class="list-group list-group-flush">

                <?php foreach ($shoppingItems as $it): ?>
                    <?php
                        $qty  = trim((string)($it['quantity'] ?? ''));
                        $unit = trim((string)($it['unit'] ?? ''));
                        $name = trim((string)($it['name'] ?? ''));

                        $left = trim($qty . ' ' . $unit);
                    ?>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <?php if ($left !== ''): ?>
                                <span><?= esc($left) ?></span>
                            <?php endif; ?>
                            <span><?= esc($name) ?></span>
                        </div>

                        <form method="post" class="m-0">
                            <input type="hidden" name="delete_id" value="<?= (int)$it['id'] ?>">
                            <button
                                type="submit"
                                class="btn btn-sm btn-outline-danger"
                                title="Eintrag löschen"
                            >
                                ✖
                            </button>
                        </form>
                    </li>

                <?php endforeach; ?>

            </ul>

        <?php else: ?>
            <div class="text-muted small">
                Deine Einkaufsliste ist leer.
            </div>
        <?php endif; ?>

        <div class="mt-3">
            <a href="user_my_recipes.php" class="btn btn-outline-secondary">Zurück</a>
        </div>

    </section>

</div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
