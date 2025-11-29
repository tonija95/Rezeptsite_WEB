<?php

// HTML-escape nur definieren, wenn nicht vorhanden
if (!function_exists('esc')) {
    function esc($s): string {
        return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

function renderRecipeCard(array $r, $actions = ['view']): string {
    $img  = !empty($r['image_url']) ? $r['image_url'] : 'img/placeholder_food.jpg';
    $id   = isset($r['id']) ? (int)$r['id'] : 0;
    $link = $id ? 'recipe.php?id=' . $id : 'recipe.php';

    $badges = [];
    if (!empty($r['tags']) && is_array($r['tags'])) {
        foreach ($r['tags'] as $vals) {
            foreach ((array)$vals as $v) {
                $v = trim((string)$v);
                if ($v !== '') $badges[] = esc($v);
            }
        }
    }

    $title = esc($r['title'] ?? 'Unbenannt');
    $desc  = esc($r['description'] ?? '');

    $html  = '<div class="col-12 col-sm-6 col-md-4">';
    $html .= '<div class="card h-100">';
    $html .= '<img src="'.esc($img).'" onerror="this.onerror=null;this.src=\'img/placeholder_food.jpg\';" alt="Food image" class="card-img-top">';
    $html .= '<div class="card-body d-flex flex-column">';
    if ($badges) {
        $html .= '<div class="d-flex flex-wrap mb-2">';
        foreach ($badges as $b) { $html .= '<span class="badge me-1 mb-1">'.$b.'</span>'; }
        $html .= '</div>';
    }
    $html .= '<h3 class="card-title h5 mb-2">'.$title.'</h3>';
    if ($desc) $html .= '<p class="card-text text-muted">'.$desc.'</p>';

    $html .= '<div class="mt-auto d-flex gap-2 flex-wrap">';
    if (in_array('view', $actions, true)) {
        $html .= '<a href="'.esc($link).'" class="btn btn-outline-secondary btn-sm">Ansehen</a>';
    }
    if ($id && in_array('delete', $actions, true)) {
        // ZENTRALER DELETE: POST an recipe_delete.php
        $html .= '<form method="post" action="recipe_delete.php" class="d-inline">';
        $html .= '<input type="hidden" name="id" value="'.$id.'">';
        $html .= '<button type="submit" class="btn btn-danger btn-sm">Löschen</button>';
        $html .= '</form>';
    }
    $html .= '</div>';

    $html .= '</div></div></div>';
    return $html;
}

function renderRecipeCards(int $count, array $recipes, array $actions = []): string {
    ob_start();
    ?>
    <?php foreach ($recipes as $r): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100">
                <?php $img = !empty($r['image_url']) ? $r['image_url'] : 'img/placeholder_food.jpg'; ?>
                <img class="card-img-top" src="<?= htmlspecialchars($img) ?>" onerror="this.onerror=null;this.src='img/placeholder_food.jpg';" alt="">
                <div class="card-body d-flex flex-column">
                    <h3 class="h6 mb-2"><?= htmlspecialchars($r['title'] ?? 'Unbenannt') ?></h3>

                    <div class="mt-auto d-flex gap-2">
                        <?php if (in_array('view', $actions, true)): ?>
                            <a class="btn btn-outline-secondary btn-sm" href="recipe.php?id="<?= (int)($r['id'] ?? 0) ?>">Ansehen</a>
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
        foreach ($badges as $b) { $html .= '<span class="badge me-1 mb-1">'.$b.'</span>'; }
        $html .= '</div>';
    }
    $html .= '<h3 class="card-title h5 mb-2">'.$title.'</h3>';
    if ($desc) $html .= '<p class="card-text text-muted">'.$desc.'</p>';
    $html .= '<a href="'.esc($link).'" class="btn btn-outline-secondary mt-auto">Ansehen</a>';
    $html .= '</div></div>';

    return $html;
}