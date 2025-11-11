<?php
// expects:
// - $TAG_OPTIONS (meal, course, specials, cuisine, level)
// - optional $showUserFilter (bool)
// - uses $_GET to persist selections

$filters = $_GET ?? [];

$checked = function($k, $v) use ($filters) {
  return (isset($filters[$k]) && is_array($filters[$k]) && in_array($v, $filters[$k])) ? 'checked' : '';
};
$selected = function($k, $v) use ($filters) {
  return (isset($filters[$k]) && $filters[$k] === $v) ? 'selected' : '';
};
$hasAny = function($k) use ($filters) {
  return !empty($filters[$k]);
};
?>

<h2 class="h5 mb-3">Filter</h2>

<form method="get" class="row g-3 align-items-start">

  <!-- Textfelder oben -->
  <div class="col-12 col-md-4 col-lg-3">
    <label class="form-label">Titel enthält …</label>
    <input type="text" class="form-control" name="title"
           value="<?= htmlspecialchars($filters['title'] ?? '') ?>">
  </div>

  <?php if (!empty($showUserFilter)): ?>
  <div class="col-12 col-md-4 col-lg-3">
    <label class="form-label">Username</label>
    <input type="text" class="form-control" name="user"
           value="<?= htmlspecialchars($filters['user'] ?? '') ?>" placeholder="z. B. anna">
  </div>
  <?php endif; ?>

  <!-- Aufklapp-Gruppen -->
  <div class="col-12">
    <div class="row g-3">

      <!-- Tageszeit -->
      <?php
        $idMeal = 'f_meal';
        $openMeal = $hasAny('meal');
      ?>
      <div class="col-12 col-md-6 col-lg-4">
        <button class="btn w-100 d-flex justify-content-between align-items-center btn-outline-secondary"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#<?= $idMeal ?>"
                aria-expanded="<?= $openMeal ? 'true':'false' ?>"
                aria-controls="<?= $idMeal ?>">
          <span>Tageszeit</span>
          <span class="chev">▾</span>
        </button>
        <div id="<?= $idMeal ?>" class="collapse mt-2 <?= $openMeal ? 'show':'' ?>">
          <div class="d-flex flex-wrap gap-3 px-1">
            <?php foreach ($TAG_OPTIONS['meal'] as $opt): ?>
              <label class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="meal[]" value="<?= htmlspecialchars($opt) ?>"
                       <?= $checked('meal',$opt) ?>>
                <span class="form-check-label"><?= htmlspecialchars($opt) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Gang -->
      <?php
        $idCourse = 'f_course';
        $openCourse = $hasAny('course');
      ?>
      <div class="col-12 col-md-6 col-lg-4">
        <button class="btn w-100 d-flex justify-content-between align-items-center btn-outline-secondary"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#<?= $idCourse ?>"
                aria-expanded="<?= $openCourse ? 'true':'false' ?>"
                aria-controls="<?= $idCourse ?>">
          <span>Gang</span>
          <span class="chev">▾</span>
        </button>
        <div id="<?= $idCourse ?>" class="collapse mt-2 <?= $openCourse ? 'show':'' ?>">
          <div class="d-flex flex-wrap gap-3 px-1">
            <?php foreach ($TAG_OPTIONS['course'] as $opt): ?>
              <label class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="course[]" value="<?= htmlspecialchars($opt) ?>"
                       <?= $checked('course',$opt) ?>>
                <span class="form-check-label"><?= htmlspecialchars($opt) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Besonderheiten -->
      <?php
        $idSpec = 'f_specials';
        $openSpec = $hasAny('specials');
      ?>
      <div class="col-12 col-md-6 col-lg-4">
        <button class="btn w-100 d-flex justify-content-between align-items-center btn-outline-secondary"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#<?= $idSpec ?>"
                aria-expanded="<?= $openSpec ? 'true':'false' ?>"
                aria-controls="<?= $idSpec ?>">
          <span>Besonderheiten</span>
          <span class="chev">▾</span>
        </button>
        <div id="<?= $idSpec ?>" class="collapse mt-2 <?= $openSpec ? 'show':'' ?>">
          <div class="d-flex flex-wrap gap-3 px-1">
            <?php foreach ($TAG_OPTIONS['specials'] as $opt): ?>
              <label class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="specials[]" value="<?= htmlspecialchars($opt) ?>"
                       <?= $checked('specials',$opt) ?>>
                <span class="form-check-label"><?= htmlspecialchars($opt) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Dropdowns (einfach) -->
      <div class="col-6 col-md-4 col-lg-2">
        <label class="form-label">Küche</label>
        <select class="form-select" name="cuisine">
          <option value="">– alle –</option>
          <?php foreach ($TAG_OPTIONS['cuisine'] as $opt): ?>
            <option value="<?= htmlspecialchars($opt) ?>" <?= $selected('cuisine',$opt) ?>>
              <?= htmlspecialchars($opt) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-6 col-md-4 col-lg-2">
        <label class="form-label">Schwierigkeit</label>
        <select class="form-select" name="level">
          <option value="">– alle –</option>
          <?php foreach ($TAG_OPTIONS['level'] as $opt): ?>
            <option value="<?= htmlspecialchars($opt) ?>" <?= $selected('level',$opt) ?>>
              <?= htmlspecialchars($opt) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Buttons -->
      <div class="col-12 col-md-8 col-lg-3 d-flex gap-2 align-items-end">
        <button class="btn btn-primary w-100">Filtern</button>
        <a href="<?= htmlspecialchars(basename($_SERVER['PHP_SELF'])) ?>" class="btn btn-outline-secondary w-100">Zurücksetzen</a>
      </div>

    </div>
  </div>
</form>

<!-- Kleines Styling für den Pfeil -->
<style>
  .chev{transition:transform .2s}
  [aria-expanded="true"] .chev{transform:rotate(180deg)}
</style>
