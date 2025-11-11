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
  'visibility'   => $isEdit ? 'public' : 'private',
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

// ZENTRAL: Tag-Optionen laden
require_once __DIR__ . '/../includes/filter_data.php';

// Einheiten für Zutaten
$UNIT_OPTIONS = ['Stk','g','ml','TL','EL','Prise'];

// Seite & Layout
$pageTitle = $isEdit ? 'Rezept bearbeiten' : 'Neues Rezept erstellen';
$role = 'user';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>
<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2"><?= htmlspecialchars($pageTitle) ?></h1>
    <p class="text-muted">
      <?= $isEdit ? 'Passe dein Rezept an und speichere die Änderungen.' : 'Lege ein neues Rezept an und speichere es in deinem Kochbuch.' ?>
    </p>
  </section>

  <!-- Formular -->
  <section class="section bg-cream mb-3 mb-md-4">
    <form action="recipe_save.php" method="post" enctype="multipart/form-data" class="row g-3">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int)$recipeId ?>">
      <?php endif; ?>

      <!-- Linke Spalte: Inhalte -->
      <div class="col-12 col-lg-8">
        <!-- Titel -->
        <label class="form-label">Rezeptname</label>
        <input type="text" name="title" class="form-control" required
               value="<?= htmlspecialchars($recipe['title']) ?>">

        <!-- Kurzbeschreibung -->
        <div class="mt-3">
          <label class="form-label">Kurzbeschreibung</label>
          <textarea name="description" rows="3" class="form-control"
                    placeholder="Worum geht’s in 1–2 Sätzen?"><?= htmlspecialchars($recipe['description']) ?></textarea>
        </div>

        <!-- Zutaten (Einzel-Items, dynamisch) -->
        <div class="mt-3">
          <label class="form-label">Zutaten</label>
          <div class="small text-muted mb-2">Menge + Einheit + Zutat (z. B. „200 g Spaghetti“). Später nutzbar für die Einkaufsliste.</div>

          <div id="ingredients-list" class="vstack gap-2">
            <?php
              $rows = max(1, count($recipe['ingredients']));
              for ($i=0; $i<$rows; $i++):
                $row = $recipe['ingredients'][$i] ?? ['quantity'=>'','unit'=>'g','name'=>''];
            ?>
              <div class="row g-2 ingredient-row">
                <div class="col-4 col-sm-3">
                  <input type="number" step="0.01" min="0" class="form-control"
                         name="ingredient_qty[]" placeholder="Menge"
                         value="<?= htmlspecialchars($row['quantity']) ?>">
                </div>
                <div class="col-4 col-sm-3">
                  <select name="ingredient_unit[]" class="form-select">
                    <?php foreach ($UNIT_OPTIONS as $u): ?>
                      <option value="<?= $u ?>" <?= ($row['unit']===$u)?'selected':''; ?>><?= $u ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-12 col-sm-5">
                  <input type="text" class="form-control"
                         name="ingredient_name[]" placeholder="Zutat"
                         value="<?= htmlspecialchars($row['name']) ?>">
                </div>
                <div class="col-12 col-sm-1 d-flex align-items-center justify-content-center">
                  <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Entfernen">×</button>
                </div>
              </div>
            <?php endfor; ?>
          </div>

          <div class="mt-3">
            <button type="button" id="add-ingredient" class="btn btn-outline-secondary btn-sm">
              ＋ Zutat hinzufügen
            </button>
          </div>
        </div>

        <!-- Zubereitung -->
        <div class="mt-3">
          <label class="form-label">Zubereitung <span class="text-muted small">(ein Schritt pro Zeile)</span></label>
          <textarea name="steps" rows="8" class="form-control" required><?= htmlspecialchars($recipe['steps']) ?></textarea>
        </div>
      </div>

      <!-- Rechte Spalte: Meta -->
      <div class="col-12 col-lg-4">
        <div class="row g-3">

          <div class="col-6">
            <label class="form-label">Dauer (min)</label>
            <input type="number" name="time_minutes" class="form-control" min="1" required
                   value="<?= htmlspecialchars($recipe['time_minutes']) ?>">
          </div>

          <div class="col-6">
            <label class="form-label">Portionen</label>
            <input type="number" name="servings" class="form-control" min="1" required
                   value="<?= htmlspecialchars($recipe['servings']) ?>">
          </div>

          <!-- Tags: Collapse-Gruppen wie beim Filter -->
          <div class="col-12">
            <label class="form-label d-block mb-2">Tags</label>

            <?php
              // kleine Helfer
              $isChecked = function(array $arr, string $v){ return in_array($v, $arr) ? 'checked' : ''; };
              $openIf    = function(array $arr){ return !empty($arr) ? 'show' : ''; };
            ?>

            <!-- Tageszeit -->
            <?php $idMeal='t_meal'; ?>
            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center"
                    type="button" data-bs-toggle="collapse" data-bs-target="#<?= $idMeal ?>"
                    aria-expanded="<?= !empty($recipe['tags']['meal']) ? 'true' : 'false' ?>"
                    aria-controls="<?= $idMeal ?>">
              <span>Tageszeit</span><span class="chev">▾</span>
            </button>
            <div id="<?= $idMeal ?>" class="collapse mt-2 <?= $openIf($recipe['tags']['meal']) ?>">
              <div class="d-flex flex-wrap gap-2 px-1">
                <?php foreach ($TAG_OPTIONS['meal'] as $opt): ?>
                  <label class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tags_meal[]"
                           value="<?= htmlspecialchars($opt) ?>" <?= $isChecked($recipe['tags']['meal'],$opt) ?>>
                    <span class="form-check-label"><?= htmlspecialchars($opt) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Gang -->
            <?php $idCourse='t_course'; ?>
            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center mt-2"
                    type="button" data-bs-toggle="collapse" data-bs-target="#<?= $idCourse ?>"
                    aria-expanded="<?= !empty($recipe['tags']['course']) ? 'true' : 'false' ?>"
                    aria-controls="<?= $idCourse ?>">
              <span>Gang</span><span class="chev">▾</span>
            </button>
            <div id="<?= $idCourse ?>" class="collapse mt-2 <?= $openIf($recipe['tags']['course']) ?>">
              <div class="d-flex flex-wrap gap-2 px-1">
                <?php foreach ($TAG_OPTIONS['course'] as $opt): ?>
                  <label class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tags_course[]"
                           value="<?= htmlspecialchars($opt) ?>" <?= $isChecked($recipe['tags']['course'],$opt) ?>>
                    <span class="form-check-label"><?= htmlspecialchars($opt) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Küchenrichtung -->
            <?php $idCuisine='t_cuisine'; ?>
            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center mt-2"
                    type="button" data-bs-toggle="collapse" data-bs-target="#<?= $idCuisine ?>"
                    aria-expanded="<?= !empty($recipe['tags']['cuisine']) ? 'true' : 'false' ?>"
                    aria-controls="<?= $idCuisine ?>">
              <span>Küchenrichtung</span><span class="chev">▾</span>
            </button>
            <div id="<?= $idCuisine ?>" class="collapse mt-2 <?= $openIf($recipe['tags']['cuisine']) ?>">
              <div class="d-flex flex-wrap gap-2 px-1">
                <?php foreach ($TAG_OPTIONS['cuisine'] as $opt): ?>
                  <label class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tags_cuisine[]"
                           value="<?= htmlspecialchars($opt) ?>" <?= $isChecked($recipe['tags']['cuisine'],$opt) ?>>
                    <span class="form-check-label"><?= htmlspecialchars($opt) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Schwierigkeit -->
            <?php $idLevel='t_level'; ?>
            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center mt-2"
                    type="button" data-bs-toggle="collapse" data-bs-target="#<?= $idLevel ?>"
                    aria-expanded="<?= !empty($recipe['tags']['level']) ? 'true' : 'false' ?>"
                    aria-controls="<?= $idLevel ?>">
              <span>Schwierigkeit</span><span class="chev">▾</span>
            </button>
            <div id="<?= $idLevel ?>" class="collapse mt-2 <?= $openIf($recipe['tags']['level']) ?>">
              <div class="d-flex flex-wrap gap-2 px-1">
                <?php foreach ($TAG_OPTIONS['level'] as $opt): ?>
                  <label class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tags_level[]"
                           value="<?= htmlspecialchars($opt) ?>" <?= $isChecked($recipe['tags']['level'],$opt) ?>>
                    <span class="form-check-label"><?= htmlspecialchars($opt) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Besonderheiten -->
            <?php $idSpec='t_specials'; ?>
            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center mt-2"
                    type="button" data-bs-toggle="collapse" data-bs-target="#<?= $idSpec ?>"
                    aria-expanded="<?= !empty($recipe['tags']['specials']) ? 'true' : 'false' ?>"
                    aria-controls="<?= $idSpec ?>">
              <span>Besondere Merkmale</span><span class="chev">▾</span>
            </button>
            <div id="<?= $idSpec ?>" class="collapse mt-2 <?= $openIf($recipe['tags']['specials']) ?>">
              <div class="d-flex flex-wrap gap-2 px-1">
                <?php foreach ($TAG_OPTIONS['specials'] as $opt): ?>
                  <label class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tags_specials[]"
                           value="<?= htmlspecialchars($opt) ?>" <?= $isChecked($recipe['tags']['specials'],$opt) ?>>
                    <span class="form-check-label"><?= htmlspecialchars($opt) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

          </div>



          <!-- Bild-Upload -->
          <div class="col-12">
            <label class="form-label">Rezeptbild</label>
            <?php if (!empty($recipe['image_url'])): ?>
              <div class="mb-2">
                <img src="<?= htmlspecialchars($recipe['image_url']) ?>"
                     onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
                     alt="Aktuelles Rezeptbild" class="img-fluid rounded">
              </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control"
                   accept="image/jpeg,image/png,image/webp">
            <div class="form-text">JPEG, PNG oder WebP · max. ~2–3 MB</div>
          </div>

          <!-- Aktionen -->
          <div class="col-12 d-flex gap-2">
            <!-- vorerst zurück zur Rezeptansicht -->
            <a href="recipe.php" class="btn btn-primary"><?= $isEdit ? 'Speichern' : 'Erstellen' ?></a>
            <a href="user_my_recipes.php" class="btn btn-outline-secondary">Abbrechen</a>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>

<!-- Kleines Styling für Pfeile -->
<style>
  .chev{transition:transform .2s}
  [aria-expanded="true"] .chev{transform:rotate(180deg)}
</style>

<!-- Dynamik für Zutatenzeilen -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const list = document.getElementById('ingredients-list');
  const addBtn = document.getElementById('add-ingredient');

  const template = `
    <div class="row g-2 ingredient-row">
      <div class="col-4 col-sm-3">
        <input type="number" step="0.01" min="0" class="form-control"
               name="ingredient_qty[]" placeholder="Menge">
      </div>
      <div class="col-4 col-sm-3">
        <select name="ingredient_unit[]" class="form-select">
          <option value="Stk">Stk</option>
          <option value="g">g</option>
          <option value="ml">ml</option>
          <option value="TL">TL</option>
          <option value="EL">EL</option>
          <option value="Prise">Prise</option>
        </select>
      </div>
      <div class="col-12 col-sm-5">
        <input type="text" class="form-control" name="ingredient_name[]" placeholder="Zutat">
      </div>
      <div class="col-12 col-sm-1 d-flex align-items-center justify-content-center">
        <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Entfernen">×</button>
      </div>
    </div>`;

  if (addBtn) {
    addBtn.addEventListener('click', () => {
      list.insertAdjacentHTML('beforeend', template);
    });
  }

  list.addEventListener('click', e => {
    if (e.target.classList.contains('remove-row')) {
      e.target.closest('.ingredient-row')?.remove();
    }
  });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
