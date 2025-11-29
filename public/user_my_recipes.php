<?php
$pageTitle = 'Meine Rezepte';
$role = 'user'; // temporär
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

// Session + Default-User
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user'])) { $_SESSION['user'] = 'anna'; }
$currentUser = $_SESSION['user'];

// Daten laden
require_once __DIR__ . '/../includes/data/recipes_store.php';
require_once __DIR__ . '/../includes/filters.php';
require_once __DIR__ . '/../includes/components/recipe_cards.php';
require_once __DIR__ . '/../includes/pre datatable/get_options.php';

// Liste meiner Rezepte
$allRecipes = recipesAll();
$myRecipes  = array_values(array_filter($allRecipes, fn($r) => ($r['user'] ?? '') === $currentUser));

// Optional: Filter anwenden
$tagFilters = normalizeTagFilters($_GET ?? []);
$filtered   = filterRecipesByTags($myRecipes, $tagFilters);
?>
<div class="container">
  <!-- Hero -->
  <section class="hero section my-3 my-md-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
    <div>
      <h1 class="h3 mb-2">Meine Rezepte</h1>
      <p class="text-muted mb-0">Hier findest du alle Rezepte, die du erstellt hast.</p>
    </div>
    <!-- Neues Rezept erstellen bleibt auf user_recipe_edit.php -->
    <a href="user_recipe_edit.php" class="btn btn-primary">Neues Rezept erstellen</a>
  </section>

  <?= renderTagFilterSection($tagFilters) ?>

  <!-- Rezeptkarten -->
  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <h2 class="h5 mb-3">Deine gespeicherten Rezepte</h2>
    <div class="row g-3">
      <?= renderRecipeCards(count($filtered), $filtered, ['view','delete']); ?>
      <?php if (empty($filtered)): ?>
        <div class="col-12"><p class="text-muted">Keine Rezepte gefunden.</p></div>
      <?php endif; ?>
    </div>
  </section>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
