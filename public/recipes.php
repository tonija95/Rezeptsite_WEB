<?php

$pageTitle = 'Rezept';
$role = 'user';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

include_once __DIR__ . '/../includes/filters.php';
include_once __DIR__ . '/../includes/components/recipe_cards.php';
?>


<div class="container ">
  <section class="hero my-3 my-md-4 py-3 px-3">
<div class="mb-3">
    <h1 class="h4 mb-0">Alle Rezepte</h1>
</div>

<div class="mb-3">
    <h2 class="h6 mb-0">Filter</h2>
</div>
<?php
$filters = readFilters();
displayFilterOptions();
?>
  </section>



  <section class="bg-cream section mb-3 mb-md-4 py-3 px-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h2 class="h6 mb-0">Ergebnisse</h2>
      <span class="text-muted small">Treffer</span>
    </div>
    <div class="row g-3">
<?php
$recipes = getRecipesForList($filters);
displayRecipeCard($recipes);
?>


    </div>
  </section>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>