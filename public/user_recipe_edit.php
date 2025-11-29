<?php
// ---------------------------------------------------------
// Create vs. Edit erkennen
// ---------------------------------------------------------
$isEdit   = isset($_GET['id']) && ctype_digit($_GET['id']);
$recipeId = $isEdit ? (int)$_GET['id'] : null;

// Beispielwerte für Edit (später per DB laden)
$recipe = [
  'title'        => $isEdit ? 'Spaghetti Aglio e Olio' : '',
  'description'  => $isEdit ? 'Kurzbeschreibung …' : '',
  'time_minutes' => $isEdit ? 15 : '',
  'servings'     => $isEdit ? 2  : '',
  'image_url'    => $isEdit ? '' : '',
  // Vorbelegte Tags (später aus DB)
  'tags' => $isEdit ? [
    'meal'     => ['Abendessen'],
    'course'   => ['Hauptgericht'],
    'cuisine'  => ['Italienisch'],
    'level'    => ['Einfach'],
    'specials' => ['Schnelle Küche'],
  ] : [
    'meal'     => [],
    'course'   => [],
    'cuisine'  => [],
    'level'    => [],
    'specials' => [],
  ],
  // Vorbelegte Zutaten (später aus DB)
  'ingredients' => $isEdit ? [
    ['quantity' => '200', 'unit' => 'g',   'name' => 'Spaghetti'],
    ['quantity' => '2',   'unit' => 'Stk', 'name' => 'Knoblauchzehen'],
    ['quantity' => '1',   'unit' => 'Stk', 'name' => 'rote Chili'],
    ['quantity' => '',    'unit' => 'EL',  'name' => 'Olivenöl'],
    ['quantity' => '',    'unit' => 'Prise','name' => 'Salz & Pfeffer'],
  ] : [],
  'steps' => $isEdit ? "Spaghetti kochen\nKnoblauch & Chili anschwitzen\nVermengen & würzen" : '',
  ];

// CSRF
session_start();
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(16)); }
$csrf = $_SESSION['csrf'];

// Default-Login: anna, falls niemand eingeloggt ist
if (empty($_SESSION['user'])) { $_SESSION['user'] = 'anna'; }
$currentUser = $_SESSION['user'];

require_once __DIR__ . '/../includes/pre datatable/get_options.php';
require_once __DIR__ . '/../includes/data/recipes_store.php';

$role = 'user';

// Modus anhand id aus dem Session-Store bestimmen
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$recipe = $id ? recipeById($id) : null;
$isEdit = $recipe !== null;

$pageTitle = $isEdit ? 'Rezept verwalten' : 'Neues Rezept erstellen';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

// Labels für Kategorien
$categoryLabels = function_exists('getTagCategoryLabels') ? getTagCategoryLabels() : [
  'meal' => 'Mahlzeit', 'course' => 'Gang', 'cuisine' => 'Küche', 'level' => 'Schwierigkeit', 'specials' => 'Besonderheiten'
];
$units = function_exists('getIngredientUnits') ? getIngredientUnits() : ['g','kg','ml','l','EL','TL','Prise','Stk','Pkg'];

// Formular-Defaults (Create)
$errors = [];
$form = [
  'title' => '', 'description' => '', 'time_minutes' => 0, 'servings' => 0,
  'image_url' => '',
  'tags' => ['meal'=>[], 'course'=>[], 'cuisine'=>[], 'level'=>[], 'specials'=>[]],
  'ingredients' => [],
  'steps' => '',
];

// POST verarbeiten (nur Create-Modus)
if (!$isEdit && $_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formwerte übernehmen
  $form['title']        = trim((string)($_POST['title'] ?? ''));
  $form['description']  = trim((string)($_POST['description'] ?? ''));
  $form['time_minutes'] = (int)($_POST['time_minutes'] ?? 0);
  $form['servings']     = (int)($_POST['servings'] ?? 0);
  $form['image_url']    = trim((string)($_POST['image_url'] ?? ''));
  foreach (getTagOptions() as $cat => $values) {
    $raw = $_POST['tags'][$cat] ?? [];
    $sel = array_values(array_filter(array_map('trim', (array)$raw)));
    $form['tags'][$cat] = array_values(array_intersect($sel, $values));
  }
  // Zutaten aus Arrays zusammensetzen
  $iq = $_POST['ingredients']['quantity'] ?? [];
  $iu = $_POST['ingredients']['unit'] ?? [];
  $in = $_POST['ingredients']['name'] ?? [];
  $form['ingredients'] = [];
  $len = max(count((array)$iq), count((array)$iu), count((array)$in));
  for ($i = 0; $i < $len; $i++) {
    $qty  = trim((string)($iq[$i] ?? ''));
    $unit = trim((string)($iu[$i] ?? ''));
    $name = trim((string)($in[$i] ?? ''));
    // Leere Zeilen ignorieren
    if ($qty === '' && $unit === '' && $name === '') continue;
    $form['ingredients'][] = ['quantity' => $qty, 'unit' => $unit, 'name' => $name];
  }
  // Steps
  $form['steps'] = trim((string)($_POST['steps'] ?? ''));

  // Action-Buttons ohne JS: Zeile hinzufügen/entfernen
  $added = isset($_POST['add_ing']);
  $removedIndex = isset($_POST['remove_ing']) ? (int)$_POST['remove_ing'] : -1;

  if ($removedIndex >= 0) {
    if (isset($form['ingredients'][$removedIndex])) {
      array_splice($form['ingredients'], $removedIndex, 1);
    }
    // Re-render ohne Speichern
  } elseif ($added) {
    $form['ingredients'][] = ['quantity'=>'', 'unit'=>'', 'name'=>''];
    // Re-render ohne Speichern
  } else {
    // Validierung + Speichern
    if ($form['title'] === '') $errors[] = 'Titel ist erforderlich.';
    if ($form['time_minutes'] < 0) $errors[] = 'Zeit muss >= 0 sein.';
    if ($form['servings'] < 0) $errors[] = 'Portionen muss >= 0 sein.';

    if (!$errors) {
      $newId = recipesAdd($form, $currentUser);
      header('Location: user_my_recipes.php?created='.$newId);
      exit;
    }
  }
}
?>
<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2"><?= htmlspecialchars($pageTitle) ?></h1>
    <p class="text-muted">
      <?= $isEdit ? 'Bearbeiten folgt später. Du kannst das Rezept ansehen oder löschen.' : 'Lege ein neues Rezept an.' ?>
    </p>
  </section>

  <?php if ($isEdit): ?>
    <?php if (!$recipe): ?>
      <div class="alert alert-warning">Rezept nicht gefunden.</div>
    <?php else: ?>
      <section class="section bg-cream mb-3 mb-md-4 py-3 px-3">
        <div class="row g-3">
          <div class="col-12 col-md-4">
            <?php $img = !empty($recipe['image_url']) ? $recipe['image_url'] : 'img/placeholder_food.jpg'; ?>
            <img src="<?= htmlspecialchars($img) ?>" onerror="this.onerror=null;this.src='img/placeholder_food.jpg';" alt="Rezeptbild" class="img-fluid rounded">
          </div>
          <div class="col-12 col-md-8">
            <h2 class="h5 mb-2"><?= htmlspecialchars($recipe['title'] ?? 'Unbenannt') ?></h2>
            <?php if (!empty($recipe['description'])): ?>
              <p class="text-muted"><?= htmlspecialchars($recipe['description']) ?></p>
            <?php endif; ?>
            <div class="text-muted small mb-3">
              <?php if (isset($recipe['time_minutes'])): ?>Zeit: <?= (int)$recipe['time_minutes'] ?> Min · <?php endif; ?>
              <?php if (isset($recipe['servings'])): ?>Portionen: <?= (int)$recipe['servings'] ?><?php endif; ?>
            </div>

            <?php if (!empty($recipe['ingredients'])): ?>
              <h3 class="h6 mt-3">Zutaten</h3>
              <ul class="mb-3">
                <?php foreach ($recipe['ingredients'] as $ing): ?>
                  <li><?= htmlspecialchars(trim(($ing['quantity'] ?? '').' '.($ing['unit'] ?? ''))) ?> <?= htmlspecialchars($ing['name'] ?? '') ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <?php if (!empty($recipe['steps'])): ?>
              <h3 class="h6">Zubereitung</h3>
              <ol class="mb-3">
                <?php foreach (preg_split('/\R+/', (string)$recipe['steps']) as $step): ?>
                  <?php $step = trim($step); if ($step === '') continue; ?>
                  <li><?= htmlspecialchars($step) ?></li>
                <?php endforeach; ?>
              </ol>
            <?php endif; ?>

            <div class="d-flex gap-2 flex-wrap">
              <a href="recipe.php?id=<?= (int)($recipe['id'] ?? 0) ?>" class="btn btn-outline-secondary btn-sm">Ansehen</a>
              <a href="user_recipe_edit.php?id=<?= (int)($recipe['id'] ?? 0) ?>" class="btn btn-primary btn-sm">Bearbeiten</a>
              <!-- Entfernt: Delete-Form -->
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>
  <?php else: ?>

    <?php if ($errors): ?>
      <div class="alert alert-danger" role="alert">
        <ul class="m-0 ps-3"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
      </div>
    <?php endif; ?>

    <section class="section bg-cream mb-3 mb-md-4 py-3 px-3">
      <form method="post" class="row g-3">
        <div class="col-12 col-lg-8">
          <label class="form-label">Rezeptname *</label>
          <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($form['title']) ?>">

          <div class="mt-3">
            <label class="form-label">Kurzbeschreibung</label>
            <textarea name="description" rows="3" class="form-control"><?= htmlspecialchars($form['description']) ?></textarea>
          </div>

          <!-- Zutaten: zwischen Kurzbeschreibung und Zubereitung -->
          <div class="mt-3">
            <label class="form-label d-block">Zutaten</label>
            <div class="d-flex flex-column gap-2">
              <?php
                $rows = !empty($form['ingredients']) ? array_values($form['ingredients']) : [['quantity'=>'', 'unit'=>'', 'name'=>'']];
                foreach ($rows as $i => $ing):
              ?>
                <div class="row g-2 align-items-center">
                  <div class="col-3">
                    <input type="text" name="ingredients[quantity][]" class="form-control" placeholder="Menge" value="<?= htmlspecialchars($ing['quantity'] ?? '') ?>">
                  </div>
                  <div class="col-3">
                    <select name="ingredients[unit][]" class="form-select">
                      <option value=""></option>
                      <?php foreach ($units as $u): ?>
                        <option value="<?= htmlspecialchars($u) ?>" <?= (($ing['unit'] ?? '') === $u) ? 'selected' : '' ?>><?= htmlspecialchars($u) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-5">
                    <input type="text" name="ingredients[name][]" class="form-control" placeholder="Zutat" value="<?= htmlspecialchars($ing['name'] ?? '') ?>">
                  </div>
                  <div class="col-1 d-grid">
                    <button type="submit" name="remove_ing" value="<?= (int)$i ?>" class="btn btn-sm btn-outline-danger" title="Zutat entfernen">×</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <button type="submit" name="add_ing" value="1" class="btn btn-sm btn-outline-secondary mt-2">Zutat hinzufügen</button>
          </div>

          <div class="mt-3">
            <label class="form-label">Zubereitungsschritte (je Zeile ein Schritt)</label>
            <textarea name="steps" rows="6" class="form-control" placeholder="Schritt 1&#10;Schritt 2&#10;Schritt 3"><?= htmlspecialchars($form['steps']) ?></textarea>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label">Dauer (min)</label>
              <input type="number" name="time_minutes" class="form-control" min="0" value="<?= (int)$form['time_minutes'] ?>">
            </div>
            <div class="col-6">
              <label class="form-label">Portionen</label>
              <input type="number" name="servings" class="form-control" min="0" value="<?= (int)$form['servings'] ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Bild-URL</label>
              <input type="url" name="image_url" class="form-control" value="<?= htmlspecialchars($form['image_url']) ?>">
            </div>

            <div class="col-12">
              <label class="form-label d-block mb-2">Tags</label>
              <?php foreach (getTagOptions() as $cat => $values): ?>
                <fieldset class="border rounded p-2 mb-2">
                  <legend class="small fw-bold px-2 mb-2"><?= htmlspecialchars($categoryLabels[$cat] ?? $cat) ?></legend>
                  <?php foreach ($values as $v): ?>
                    <?php $cid = 't_'.$cat.'_'.preg_replace('/\W+/', '_', strtolower($v)); ?>
                    <div class="form-check form-check-inline mb-1">
                      <input class="form-check-input" type="checkbox"
                             name="tags[<?= htmlspecialchars($cat) ?>][]"
                             id="<?= htmlspecialchars($cid) ?>"
                             value="<?= htmlspecialchars($v) ?>"
                             <?= in_array($v, $form['tags'][$cat] ?? [], true) ? 'checked' : '' ?>>
                      <label class="form-check-label small" for="<?= htmlspecialchars($cid) ?>"><?= htmlspecialchars($v) ?></label>
                    </div>
                  <?php endforeach; ?>
                </fieldset>
              <?php endforeach; ?>
            </div>

            <div class="col-12 d-flex gap-2">
              <button type="submit" class="btn btn-primary">Erstellen</button>
              <a href="user_my_recipes.php" class="btn btn-outline-secondary">Abbrechen</a>
            </div>
          </div>
        </div>
      </form>
    </section>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
