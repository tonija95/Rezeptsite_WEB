<?php
require_once __DIR__ . '/pre datatable/get_options.php';

function normalizeTagFilters(array $in): array {
    $opts = getTagOptions();
    $out = [];
    foreach ($opts as $cat => $values) {
        $raw  = $in[$cat] ?? [];
        $vals = array_values(array_filter(array_map('trim', (array)$raw)));
        $out[$cat] = array_values(array_intersect($vals, $values));
    }
    return $out;
}

function extractRecipeTagsByCategory(array $r): array {
    $result = [];
    foreach (getTagOptions() as $cat => $_) {
        $vals = [];
        if (!empty($r['tags'][$cat])) {
            foreach ((array)$r['tags'][$cat] as $v) {
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
        if (empty($tags[$cat]) || !array_intersect($need, $tags[$cat])) return false;
    }
    return true;
}

function filterRecipesByTags(array $recipes, array $filters): array {
    return array_values(array_filter($recipes, fn($r) => recipeMatchesTagFilters($r, $filters)));
}

function renderTagFilterForm(array $current): string {
    $opts = getTagOptions();
    $labels = function_exists('getTagCategoryLabels') ? getTagCategoryLabels() : [];
    
    $html = '<form method="get" class="filter-form">';
    foreach ($opts as $cat => $values) {
        $label = $labels[$cat] ?? $cat;
        $html .= '<fieldset class="mb-3"><legend class="small fw-bold mb-1">'.htmlspecialchars($label).'</legend>';
        foreach ($values as $v) {
            $id = 'f_'.$cat.'_'.preg_replace('/\W+/','_', strtolower($v));
            $checked = in_array($v, $current[$cat] ?? [], true) ? ' checked' : '';
            $html .= '<div class="form-check form-check-inline mb-1">';
            $html .= '<input class="form-check-input" type="checkbox" name="'.htmlspecialchars($cat).'[]" id="'.htmlspecialchars($id).'" value="'.htmlspecialchars($v).'"'.$checked.'>';
            $html .= '<label class="form-check-label small" for="'.htmlspecialchars($id).'">'.htmlspecialchars($v).'</label>';
            $html .= '</div>';
        }
        $html .= '</fieldset>';
    }
    $html .= '<button type="submit" class="btn btn-sm btn-primary">Filtern</button> ';
    $html .= '<a href="'.htmlspecialchars(basename($_SERVER['PHP_SELF'])).'" class="btn btn-sm btn-outline-secondary">Zurücksetzen</a>';
    $html .= '</form>';
    return $html;
}

/**
 * Rendert die komplette Filter-Section mit Collapse-Toggle
 */
function renderTagFilterSection(array $currentFilters = []): string {
    $filtersOpen = false;
    foreach ($currentFilters as $cat => $vals) {
        if (!empty($vals)) {
            $filtersOpen = true;
            break;
        }
    }
    
    ob_start();
    ?>
    <section class="section bg-cream mb-3 mb-md-4 py-3 px-3">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h5 m-0">Filter</h2>
            <button
                class="btn btn-outline-secondary d-inline-flex align-items-center gap-1"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#recipeFilters"
                aria-expanded="<?= $filtersOpen ? 'true' : 'false' ?>"
                aria-controls="recipeFilters">
                <span><?= $filtersOpen ? 'Filter verbergen' : 'Filter anzeigen' ?></span>
                <span class="chev" aria-hidden="true">▾</span>
            </button>
        </div>

        <div id="recipeFilters" class="collapse <?= $filtersOpen ? 'show' : '' ?> mt-3">
            <?= renderTagFilterForm($currentFilters) ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
