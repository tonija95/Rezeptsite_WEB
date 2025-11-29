<?php
require_once __DIR__ . '/pre datatable/get_options.php';

function normalizeTagFilters(array $in): array {
    $opts = getTagOptions();
    $out = [];
    foreach ($opts as $cat => $_) {
        $raw  = $in[$cat] ?? [];
        $vals = array_values(array_filter(array_map('trim', (array)$raw)));
        $vals = array_values(array_intersect($vals, $opts[$cat]));
        $out[$cat] = $vals;
    }
    return $out;
}

function extractRecipeTagsByCategory(array $r): array {
    $result = [];
    foreach (getTagOptions() as $cat => $_) {
        $vals = [];
        if (!empty($r['tags'][$cat])) {
            $source = is_array($r['tags'][$cat]) ? $r['tags'][$cat] : [$r['tags'][$cat]];
            foreach ($source as $v) {
                $v = trim((string)$v);
                if ($v !== '') $vals[] = $v;
            }
        }
        $result[$cat] = $vals;
    }
    return $result;
}

function recipeMatchesTagFilters(array $recipe, array $filters): bool {
    $tags = extractRecipeTagsByCategory($recipe);
    foreach ($filters as $cat => $need) {
        if (empty($need)) continue;
        if (!isset($tags[$cat]) || !array_intersect($need, $tags[$cat])) return false;
    }
    return true;
}

function filterRecipesByTags(array $recipes, array $filters): array {
    return array_values(array_filter($recipes, fn($r) => recipeMatchesTagFilters($r, $filters)));
}

function renderTagFilterForm(array $current): string {
    $opts = getTagOptions();
    $categoryLabels = [
        'meal'     => 'Tageszeit',
        'course'   => 'Gang',
        'cuisine'  => 'Küche',
        'level'    => 'Schwierigkeit',
        'specials' => 'Besonderheiten',
    ];
    
    $html = '<form method="get" class="filter-form">';
    foreach ($opts as $cat => $values) {
        $label = $categoryLabels[$cat] ?? $cat;
        $html .= '<fieldset class="mb-3"><legend class="small fw-bold mb-1">'.esc($label).'</legend>';
        foreach ($values as $v) {
            $id = 'f_'.$cat.'_'.preg_replace('/\W+/','_', strtolower($v));
            $checked = in_array($v, $current[$cat] ?? [], true) ? ' checked' : '';
            $html .= '<div class="form-check form-check-inline mb-1">';
            $html .= '<input class="form-check-input" type="checkbox" name="'.$cat.'[]" id="'.$id.'" value="'.esc($v).'"'.$checked.'>';
            $html .= '<label class="form-check-label small" for="'.$id.'">'.esc($v).'</label>';
            $html .= '</div>';
        }
        $html .= '</fieldset>';
    }
    $html .= '<button type="submit" class="btn btn-sm btn-primary">Filtern</button> ';
    $html .= '<a href="recipes.php" class="btn btn-sm btn-outline-secondary">Zurücksetzen</a>';
    $html .= '</form>';
    return $html;
}
