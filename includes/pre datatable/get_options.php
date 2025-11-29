<?php
// ---- Zentrale Tag-Optionen ----
  function getTagOptions(): array {
return [
  'meal'     => ['Frühstück','Mittagessen','Abendessen'],
  'course'   => ['Vorspeise','Hauptgericht','Beilage','Suppe','Salat','Snack','Dessert'],
  'cuisine'  => ['Italienisch','Asiatisch','Indisch','Mexikanisch','Österreichisch','Deutsch','Französisch','Orientalisch','Mediterran'],
  'level'    => ['Einfach','Mittel','Anspruchsvoll'],
  'specials' => ['Schnelle Küche','Vegan','Vegetarisch','Glutenfrei','Laktosefrei','Low-Carb','Proteinreich'],
];

//--- 'Zentrale Einheiten-Optionen' ----
  }

  function getUnitOptions(): array {
  return ['Stk','g','ml','TL','EL','Prise'];
}