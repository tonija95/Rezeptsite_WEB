<?php

// esc() wird in boot.php definiert, hier nicht nochmal

function renderRecipeCard(array $r): string {
  $img   = !empty($r['image_url']) ? esc($r['image_url']) : 'img/placeholder_food.jpg';
  $title = esc($r['title'] ?? 'Unbenannt');
  $desc  = !empty($r['description']) ? esc(mb_substr($r['description'], 0, 80)) . '...' : '';
  $id    = (int)($r['id'] ?? 0);
  $time  = isset($r['time_minutes']) ? (int)$r['time_minutes'] : 0;
  $servings = isset($r['servings']) ? (int)$r['servings'] : 0;

  // Tags sammeln
  $tagsFlat = [];
  if (!empty($r['tags']) && is_array($r['tags'])) {
    foreach ($r['tags'] as $vals) {
      if (is_array($vals)) {
        foreach ($vals as $v) {
          $v = trim((string)$v);
          if ($v !== '') $tagsFlat[] = $v;
        }
      } else {
        $v = trim((string)$vals);
        if ($v !== '') $tagsFlat[] = $v;
      }
    }
  }
  $tagsFlat = array_unique($tagsFlat);

  ob_start();
  ?>
  <div class="col-12 col-sm-6 col-lg-4">
    <article class="card h-100 shadow-sm">
      <img class="card-img-top" src="<?= $img ?>" onerror="this.onerror=null;this.src='img/placeholder_food.jpg';" alt="<?= $title ?>">
      <div class="card-body d-flex flex-column">
        <h3 class="h6 mb-2"><?= $title ?></h3>

        <!-- Tags als Badges - ALLE anzeigen -->
        <?php if (!empty($tagsFlat)): ?>
          <div class="d-flex flex-wrap gap-1 mb-2">
            <?php foreach ($tagsFlat as $tag): ?>
              <span class="badge bg-secondary" style="font-size:0.7rem;"><?= esc($tag) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($desc): ?>
          <p class="text-muted small mb-2"><?= $desc ?></p>
        <?php endif; ?>

        <div class="small text-muted mb-3">
          <?php if ($time > 0): ?>
            <span>⏱ <?= $time ?> min</span>
          <?php endif; ?>
          <?php if ($servings > 0): ?>
            <span class="ms-2">👥 <?= $servings ?></span>
          <?php endif; ?>
        </div>

        <div class="mt-auto d-flex gap-2 flex-wrap">
          <a href="recipe.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-sm">Ansehen</a>
          <!-- optional: Bearbeiten/Löschen -->
          <?php if (isset($r['showEdit']) && $r['showEdit']): ?>
            <a href="user_recipe_edit.php?id=<?= $id ?>" class="btn btn-outline-primary btn-sm">Bearbeiten</a>
          <?php endif; ?>
          <?php if (isset($r['showDelete']) && $r['showDelete']): ?>
            <button type="button" class="btn btn-outline-danger btn-sm" 
              onclick="if(confirm('Wirklich löschen?')) location.href='user_recipe_delete.php?id=<?= $id ?>';">
              Löschen
            </button>
          <?php endif; ?>
        </div>
      </div>
    </article>
  </div>
  <?php
  return ob_get_clean();
}

function renderRecipeCards(int $count, array $recipes, array $actions = []): string {
    ob_start();
    ?>
    <?php foreach ($recipes as $r): ?>
        <?php
        // Tags sammeln
        $tagsFlat = [];
        if (!empty($r['tags']) && is_array($r['tags'])) {
          foreach ($r['tags'] as $vals) {
            if (is_array($vals)) {
              foreach ($vals as $v) {
                $v = trim((string)$v);
                if ($v !== '') $tagsFlat[] = $v;
              }
            } else {
              $v = trim((string)$vals);
              if ($v !== '') $tagsFlat[] = $v;
            }
          }
        }
        $tagsFlat = array_unique($tagsFlat);
        ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100">
                <?php $img = !empty($r['image_url']) ? $r['image_url'] : 'img/placeholder_food.jpg'; ?>
                <img class="card-img-top" src="<?= htmlspecialchars($img) ?>" onerror="this.onerror=null;this.src='img/placeholder_food.jpg';" alt="">
                <div class="card-body d-flex flex-column">
                    <h3 class="h6 mb-2"><?= htmlspecialchars($r['title'] ?? 'Unbenannt') ?></h3>

                    <!-- Tags als Badges - ALLE anzeigen -->
                    <?php if (!empty($tagsFlat)): ?>
                      <div class="d-flex flex-wrap gap-1 mb-2">
                        <?php foreach ($tagsFlat as $tag): ?>
                          <span class="badge bg-secondary" style="font-size:0.7rem;"><?= esc($tag) ?></span>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>

                    <div class="mt-auto d-flex gap-2">
                        <?php if (in_array('view', $actions, true)): ?>
                            <a class="btn btn-outline-secondary btn-sm" href="recipe.php?id=<?= (int)($r['id'] ?? 0) ?>">Ansehen</a>
                        <?php endif; ?>

                        <?php if (in_array('edit', $actions, true)): ?>
                            <a class="btn btn-primary btn-sm" href="user_recipe_edit.php?id=<?= (int)($r['id'] ?? 0) ?>">Bearbeiten</a>
                        <?php endif; ?>

                        <?php if (in_array('delete', $actions, true)): ?>
                            <!-- ZENTRALER DELETE: POST an recipe_delete.php -->
                            <form method="post" action="recipe_delete.php" class="d-inline">
                                <input type="hidden" name="id" value="<?= (int)($r['id'] ?? 0) ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Löschen</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php
    return ob_get_clean();
}

// Kompakte Karte (gleiches Badge-/Button-Verhalten)
function renderCompactRecipeCard(array $r): string {
    $img  = !empty($r['image_url']) ? $r['image_url'] : 'img/placeholder_food.jpg';
    $id   = isset($r['id']) ? (int)$r['id'] : 0;
    $link = $id ? 'recipe.php?id=' . $id : 'recipe.php';

    $badges = [];
    if (!empty($r['tags']) && is_array($r['tags'])) {
        foreach ($r['tags'] as $vals) {
            if (is_array($vals)) {
                foreach ($vals as $v) {
                    $v = trim((string)$v);
                    if ($v !== '') $badges[] = esc($v);
                }
            } else {
                $v = trim((string)$vals);
                if ($v !== '') $badges[] = esc($v);
            }
        }
    }

    $title = esc($r['title'] ?? 'Unbenannt');
    $desc  = esc($r['description'] ?? '');

    $html  = '<div class="card h-100 mb-4">';
    $html .= '<img src="'.esc($img).'" onerror="this.onerror=null;this.src=\'img/placeholder_food.jpg\';" alt="'.$title.'" class="card-img-top">';
    $html .= '<div class="card-body d-flex flex-column">';
    if ($badges) {
        $html .= '<div class="d-flex flex-wrap mb-2">';
        foreach ($badges as $b) { $html .= '<span class="badge bg-secondary me-1 mb-1" style="font-size:0.7rem;">'.$b.'</span>'; }
        $html .= '</div>';
    }
    $html .= '<h3 class="card-title h5 mb-2">'.$title.'</h3>';
    if ($desc) $html .= '<p class="card-text text-muted">'.$desc.'</p>';
    $html .= '<a href="'.esc($link).'" class="btn btn-outline-secondary mt-auto">Ansehen</a>';
    $html .= '</div></div>';

    return $html;
}