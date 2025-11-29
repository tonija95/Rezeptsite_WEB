<?php

// HTML-escape nur definieren, wenn nicht vorhanden
if (!function_exists('esc')) {
    function esc($s): string {
        return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

function renderRecipeCard(array $r): string {
    $img  = !empty($r['image_url']) ? $r['image_url'] : 'img/placeholder_food.jpg';
    $id   = isset($r['id']) ? (int)$r['id'] : 0;
    $link = $id ? 'recipe.php?id=' . $id : 'recipe.php';

    // Alle Badges aus allen Kategorien sammeln
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

    $html  = '<div class="col-12 col-sm-6 col-lg-4">';
    $html .= '<div class="card h-100">';
    $html .= '<img src="'.esc($img).'" onerror="this.onerror=null;this.src=\'img/placeholder_food.jpg\';" alt="Food image" class="card-img-top">';
    // Flex-Column, damit der Button unten steht
    $html .= '<div class="card-body d-flex flex-column">';
    // Badges nebeneinander mit Wrap
    if ($badges) {
        $html .= '<div class="d-flex flex-wrap mb-2">';
        foreach ($badges as $b) { $html .= '<span class="badge me-1 mb-1">'.$b.'</span>'; }
        $html .= '</div>';
    }
    $html .= '<h3 class="card-title h5 mb-2">'.$title.'</h3>';
    if ($desc) $html .= '<p class="card-text text-muted">'.$desc.'</p>';
    $html .= '<a href="'.esc($link).'" class="btn btn-outline-secondary mt-auto">Ansehen</a>';
    $html .= '</div></div></div>';

    return $html;
}

function renderRecipeCards(int $count = 6, ?array $recipes = null): string {
    if (!is_array($recipes) || $count <= 0) return '';
    $slice = array_slice($recipes, 0, $count);
    $out = '';
    foreach ($slice as $r) { $out .= renderRecipeCard($r); }
    return $out;
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