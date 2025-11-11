<?php
// ---- Zentrale Tag-Optionen ----
function getTagOptions(): array {
  return [
    'meal'     => ['Frühstück','Mittagessen','Abendessen','Snack','Dessert'],
    'course'   => ['Vorspeise','Hauptgericht','Beilage','Suppe','Salat','Nachspeise'],
    'cuisine'  => ['Italienisch','Asiatisch','Indisch','Mexikanisch','Österreichisch','Deutsch','Französisch','Orientalisch','Mediterran'],
    'level'    => ['Einfach','Mittel','Anspruchsvoll'],
    'specials' => ['Schnelle Küche','Vegan','Vegetarisch','Glutenfrei','Laktosefrei','Low-Carb','Proteinreich'],
  ];
}

// ---- Filter-Input normalisieren (Mehrfachauswahl unterstützt) ----
function normalizeRecipeFilters(array $in): array {
  $arr = fn($k) => array_values(array_filter(array_map('trim', (array)($in[$k] ?? []))));
  $get = fn($k) => (isset($in[$k]) && $in[$k] !== '') ? trim((string)$in[$k]) : null;

  return [
    'title'    => $get('title'),
    'user'     => $get('user'),
    'meal'     => $arr('meal'),
    'course'   => $arr('course'),
    'cuisine'  => $arr('cuisine'),
    'level'    => $arr('level'),
    'specials' => $arr('specials'),
  ];
}

// ---- Filtern (innerhalb einer Gruppe = ODER, zwischen Gruppen = UND) ----
function filterRecipesArray(array $recipes, array $f): array {
  $hasAny = fn($need, $have) => empty($need) || array_intersect($need, $have);

  return array_values(array_filter($recipes, function($r) use ($f, $hasAny) {
    if (!$hasAny($f['meal'],     $r['meal']))     return false;
    if (!$hasAny($f['course'],   $r['course']))   return false;
    if (!$hasAny($f['cuisine'],  $r['cuisine']))  return false;
    if (!$hasAny($f['level'],    $r['level']))    return false;
    if (!$hasAny($f['specials'], $r['specials'])) return false;

    if ($f['user']  && stripos($r['user'],  $f['user'])  === false) return false;
    if ($f['title'] && stripos($r['title'], $f['title']) === false) return false;
    return true;
  }));
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
