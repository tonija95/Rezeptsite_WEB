<?php
$pageTitle = 'Startseite';


include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/filters.php';
require_once __DIR__ . '/../includes/components/recipe_cards.php';

$indexData = getIndexRecipes();
$newest = $indexData['newest'];
$random = $indexData['random'];
?>
<main>
<div class="container">

<section class="hero section my-3 my-md-4 text-center">
  <h1 class="display-6 mb-3">Willkommen auf unserer Rezeptseite!</h1>
  <p class="lead text-muted mb-4">
    Entdecke neue Lieblingsrezepte – frisch, vielfältig und einfach nachzukochen.
  </p>

  <div class="d-flex gap-2 justify-content-center flex-wrap">
    <a href="recipes.php" class="btn btn-primary">Alle Rezepte entdecken</a>

    <?php
      $role = $_SESSION['role'] ?? 'guest';
    ?>

    <?php if ($role === 'guest'): ?>
      <a href="registration.php" class="btn btn-outline-secondary">Registrieren</a>

    <?php elseif ($role === 'user'): ?>
      <a href="user_my_recipes.php" class="btn btn-outline-secondary">Meine Rezepte</a>

    <?php elseif ($role === 'admin'): ?>
      <a href="admin.php" class="btn btn-outline-secondary">Admin Dashboard</a>
    <?php endif; ?>
  </div>
</section>


  <section class="section bg-cream mb-3 mb-md-4 py-4 px-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h2 class="h5 mb-0">🆕 Neu hinzugefügt</h2>

    </div>

    <div class="row g-3">
      <?php if (!empty($newest)): ?>
        <?php displayRecipeCard($newest); ?>
      <?php else: ?>
        <p class="text-muted small mb-0">Noch keine Rezepte vorhanden.</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="section bg-cream mb-3 mb-md-4 py-4 px-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h2 class="h5 mb-0">✨ Zufällige Inspiration</h2>

    </div>

    <div class="row g-3">
      <?php if (!empty($random)): ?>
        <?php displayRecipeCard($random); ?>
      <?php else: ?>
        <p class="text-muted small mb-0">Noch keine Rezepte vorhanden.</p>
      <?php endif; ?>
    </div>
  </section>

</div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
