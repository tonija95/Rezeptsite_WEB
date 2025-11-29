<?php
$pageTitle = 'Rezept';
// zum Testen kannst du hier 'guest' | 'user' | 'admin' setzen:
$role = 'admin';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/pre datatable/recipe_examples.php';
require_once __DIR__ . '/../includes/components/recipe_cards.php';

// Escape-Helfer (kollisionssicher)
if (!function_exists('esc')) {
  function esc($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
}

// id aus GET
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : 0;

// Rezeptdaten laden und Rezept per id finden
$recipes = function_exists('getExampleRecipes') ? getExampleRecipes() : [];
$recipe  = null;
foreach ($recipes as $r) {
  if (isset($r['id']) && (int)$r['id'] === $id) { $recipe = $r; break; }
}
if (!$recipe) {
  http_response_code(404);
  echo '<div class="container py-4"><p class="alert alert-warning">Kein Rezept gefunden. <a href="recipes.php">Zurück zur Übersicht</a></p></div>';
  include __DIR__ . '/../includes/footer.php';
  exit;
}

// Hilfen
$img = !empty($recipe['image_url']) ? $recipe['image_url'] : 'img/placeholder_food.jpg';
$tagsFlat = [];
if (!empty($recipe['tags']) && is_array($recipe['tags'])) {
  foreach ($recipe['tags'] as $vals) {
    if (is_array($vals)) foreach ($vals as $v) $tagsFlat[] = (string)$v;
    else $tagsFlat[] = (string)$vals;
  }
}

// Tags des aktuellen Rezepts flach sammeln
$currentTags = [];
if (!empty($recipe['tags']) && is_array($recipe['tags'])) {
  foreach ($recipe['tags'] as $vals) {
    if (is_array($vals)) { foreach ($vals as $v) { $v = trim((string)$v); if ($v !== '') $currentTags[] = $v; } }
    else { $v = trim((string)$vals); if ($v !== '') $currentTags[] = $v; }
  }
}
$currentTags = array_unique($currentTags);

// ---- Ähnliche Rezepte: Block (Tags match + max. 3) ----
$similar = array_values(array_filter($recipes, function($x) use ($recipe, $currentTags) {
  if (!isset($x['id']) || (int)$x['id'] === (int)$recipe['id']) return false;
  if (empty($x['tags']) || !is_array($x['tags']) || empty($currentTags)) return false;

  $tags = [];
  foreach ($x['tags'] as $vals) {
    if (is_array($vals)) { foreach ($vals as $v) { $v = trim((string)$v); if ($v !== '') $tags[] = $v; } }
    else { $v = trim((string)$vals); if ($v !== '') $tags[] = $v; }
  }
  $tags = array_unique($tags);

  return count(array_intersect($currentTags, $tags)) > 0;
}));
$similar = array_slice($similar, 0, 3);
// ---- Ende Block ----
?>
<div class="container">

  <div class="row justify-content-center g-4">
    <article class="col-12 col-md-8">

      <!-- Titel -->
      <section class="section hero my-3 my-md-4">
        <h1 class="fs-3 mb-2"><?= esc($recipe['title'] ?? 'Unbenannt') ?></h1>
        <?php if (!empty($recipe['description'])): ?>
          <p class="mb-3 text-muted"><?= esc($recipe['description']) ?></p>
        <?php endif; ?>

        <div class="d-flex flex-wrap gap-3 small text-muted">
          <?php if (!empty($recipe['user'])): ?>
            <span>👩‍🍳 von <strong><?= esc($recipe['user']) ?></strong></span>
          <?php endif; ?>
          <?php if (isset($recipe['time_minutes'])): ?>
            <span>⏱ Zubereitung: <?= esc((string)$recipe['time_minutes']) ?> min</span>
          <?php endif; ?>
          <?php if (isset($recipe['servings'])): ?>
            <span>👥 <?= esc((string)$recipe['servings']) ?> Portionen</span>
          <?php endif; ?>
        </div>

        <!-- Tags dynamisch -->
        <?php if (!empty($tagsFlat)): ?>
          <div class="d-flex flex-wrap gap-2 my-3">
            <?php foreach ($tagsFlat as $t): ?>
              <span class="badge rounded-pill" style="background:var(--color-accent);color:var(--color-dark);"><?= esc($t) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>

      <!-- Bild + Zutaten -->
      <section class="section bg-cream mb-3 mb-md-4">
        <div class="row g-3 align-items-start">
          <div class="col-12 col-lg-6">
            <figure class="m-0">
              <picture>
                <img
                  src="<?= esc($img) ?>"
                  onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
                  alt="<?= esc($recipe['title'] ?? 'Rezeptbild') ?>"
                  class="img-fluid rounded recipe-img">
              </picture>
              <!-- optionales Caption -->
              <?php if (!empty($recipe['description'])): ?>
                <figcaption class="text-muted small mt-1"><?= esc($recipe['description']) ?></figcaption>
              <?php endif; ?>
            </figure>
          </div>

          <!-- Zutaten -->
          <div class="col-12 col-lg-6">
            <h2 class="fs-5 mb-3">Zutaten</h2>
            <?php if (!empty($recipe['ingredients']) && is_array($recipe['ingredients'])): ?>
              <ul class="mb-0">
                <?php foreach ($recipe['ingredients'] as $ing): ?>
                  <?php
                    if (is_array($ing)) {
                      $qty  = trim((string)($ing['qty'] ?? ''));
                      $unit = trim((string)($ing['unit'] ?? ''));
                      $item = trim((string)($ing['item'] ?? ''));
                      $line = trim($qty . ' ' . $unit . ' ' . $item);
                      if ($line !== '') echo '<li>'.esc($line).'</li>';
                    } else {
                      $val = trim((string)$ing);
                      if ($val !== '') echo '<li>'.esc($val).'</li>';
                    }
                  ?>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p class="text-muted mb-0">Keine Zutaten hinterlegt.</p>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- Schritte -->
      <section class="section bg-cream mb-3 mb-md-4">
        <div class="col-12 col-lg-6">
          <h2 class="fs-5 mb-3">Zubereitung</h2>
          <?php if (!empty($recipe['steps'])): ?>
            <?php if (is_array($recipe['steps'])): ?>
              <ol class="mb-0">
                <?php foreach ($recipe['steps'] as $st): ?>
                  <li><?= esc($st) ?></li>
                <?php endforeach; ?>
              </ol>
            <?php else: ?>
              <div class="mb-0"><?= nl2br(esc((string)$recipe['steps'])) ?></div>
            <?php endif; ?>
          <?php else: ?>
            <p class="text-muted mb-0">Keine Schritte hinterlegt.</p>
          <?php endif; ?>
        </div>
      </section>
    </article>

    <!-- Ähnliche Rezepte -->
    <aside class="col-12 col-lg-4">
      <section class="section mt-4" style="top:5rem;">
        <h2 class="fs-6 mb-4">Ähnliche Rezepte</h2>
        <?php foreach ($similar as $s) { echo renderCompactRecipeCard($s); } ?>
      </section>
    </aside>
  </div>

</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
