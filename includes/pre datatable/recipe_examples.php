<?php
// 6 Beispielrezepte

function getExampleRecipe1(): array {
  return [
    'id' => 1,
    'user' => 'anna',
    'title' => 'Rührei mit frischen Kräutern',
    'description' => 'Schnelles, proteinreiches Frühstück mit Petersilie und Schnittlauch.',
    'time_minutes' => 10,
    'servings' => 2,
    'tags' => [
      'meal' => ['Frühstück'],
      'level' => ['Einfach'],
      'specials' => ['Schnelle Küche','Proteinreich'],
    ],
    'ingredients' => [
      ['qty'=>4, 'unit'=>'Stk', 'item'=>'Eier'],
      ['qty'=>1, 'unit'=>'EL', 'item'=>'Butter'],
      ['qty'=>2, 'unit'=>'EL', 'item'=>'Milch'],
      ['qty'=>1, 'unit'=>'Prise', 'item'=>'Salz'],
      ['qty'=>1, 'unit'=>'Prise', 'item'=>'Pfeffer'],
      ['qty'=>1, 'unit'=>'EL', 'item'=>'Schnittlauch, gehackt'],
      ['qty'=>1, 'unit'=>'EL', 'item'=>'Petersilie, gehackt'],
    ],
    'steps' => [
      'Eier mit Milch, Salz und Pfeffer verquirlen.',
      'Butter in der Pfanne schmelzen.',
      'Eimasse bei mittlerer Hitze stocken lassen und rühren.',
      'Kräuter unterheben und sofort servieren.',
    ],
    'image_url' => 'https://picsum.photos/400/300?random=11',
  ];
}

function getExampleRecipe2(): array {
  return [
    'id' => 2,
    'user' => 'anna',
    'title' => 'Pasta Pomodoro',
    'description' => 'Klassische Tomatensauce mit frischem Basilikum.',
    'time_minutes' => 25,
    'servings' => 4,
    'tags' => [
      'cuisine' => ['Italienisch'],
      'level' => ['Einfach'],
    ],
    'ingredients' => [
      ['qty'=>400, 'unit'=>'g', 'item'=>'Spaghetti'],
      ['qty'=>2, 'unit'=>'EL', 'item'=>'Olivenöl'],
      ['qty'=>2, 'unit'=>'Stk', 'item'=>'Knoblauchzehen'],
      ['qty'=>1, 'unit'=>'Dose', 'item'=>'Tomaten (stückig)'],
      ['qty'=>1, 'unit'=>'TL', 'item'=>'Zucker'],
      ['qty'=>1, 'unit'=>'Handvoll', 'item'=>'Basilikum'],
      ['qty'=>1, 'unit'=>'Prise', 'item'=>'Salz & Pfeffer'],
    ],
    'steps' => [
      'Pasta nach Packungsangabe kochen.',
      'Knoblauch in Olivenöl anschwitzen.',
      'Tomaten und Zucker zugeben, 10 Minuten köcheln.',
      'Mit Salz/Pfeffer abschmecken, Basilikum unterheben.',
      'Pasta mit der Sauce mischen und servieren.',
    ],
    'image_url' => 'https://picsum.photos/400/300?random=12',
  ];
}

function getExampleRecipe3(): array {
  return [
    'id' => 3,
    'user' => 'susi',
    'title' => 'Asiatische Gemüsepfanne mit Tofu',
    'description' => 'Viel Gemüse mit Soja‑Ingwer‑Sauce – vegan.',
    'time_minutes' => 20,
    'servings' => 3,
    'tags' => [
      'cuisine' => ['Asiatisch'],
      'specials' => ['Vegan'],
    ],
    'ingredients' => [
      ['qty'=>200, 'unit'=>'g', 'item'=>'Tofu, gewürfelt'],
      ['qty'=>1, 'unit'=>'Stk', 'item'=>'Paprika'],
      ['qty'=>1, 'unit'=>'Stk', 'item'=>'Zucchini'],
      ['qty'=>200, 'unit'=>'g', 'item'=>'Brokkoli'],
      ['qty'=>2, 'unit'=>'EL', 'item'=>'Sojasauce'],
      ['qty'=>1, 'unit'=>'EL', 'item'=>'Sesamöl'],
      ['qty'=>1, 'unit'=>'TL', 'item'=>'Ingwer, gerieben'],
    ],
    'steps' => [
      'Gemüse in Stücke schneiden, Tofu würfeln.',
      'Tofu in Sesamöl anbraten, herausnehmen.',
      'Gemüse kurz scharf anbraten.',
      'Mit Sojasauce und Ingwer würzen, Tofu zurück in die Pfanne.',
    ],
    'image_url' => 'https://picsum.photos/400/300?random=13',
  ];
}

function getExampleRecipe4(): array {
  return [
    'id' => 4,
    'user' => 'susi',
    'title' => 'Cremige Tomatensuppe',
    'description' => 'Wärmende Suppe – pur oder mit Brot.',
    'time_minutes' => 30,
    'servings' => 4,
    'tags' => [
      'course' => ['Suppe'],
      'specials' => ['Glutenfrei'],
    ],
    'ingredients' => [
      ['qty'=>1, 'unit'=>'Stk', 'item'=>'Zwiebel'],
      ['qty'=>2, 'unit'=>'Stk', 'item'=>'Knoblauchzehen'],
      ['qty'=>1, 'unit'=>'EL', 'item'=>'Olivenöl'],
      ['qty'=>800, 'unit'=>'g', 'item'=>'Tomaten (passiert)'],
      ['qty'=>300, 'unit'=>'ml', 'item'=>'Gemüsebrühe'],
      ['qty'=>100, 'unit'=>'ml', 'item'=>'Sahne'],
      ['qty'=>1, 'unit'=>'Prise', 'item'=>'Salz & Pfeffer'],
    ],
    'steps' => [
      'Zwiebel und Knoblauch in Öl anschwitzen.',
      'Tomaten und Brühe zugeben, 15 Minuten köcheln.',
      'Sahne einrühren, mit Salz/Pfeffer abschmecken, pürieren.',
    ],
    'image_url' => 'https://picsum.photos/400/300?random=14',
  ];
}

function getExampleRecipe5(): array {
  return [
    'id' => 5,
    'user' => 'susi',
    'title' => 'Indisches Dal mit roten Linsen',
    'description' => 'Würziges Dal – proteinreich und vegan.',
    'time_minutes' => 35,
    'servings' => 4,
    'tags' => [
      'cuisine' => ['Indisch'],
      'specials' => ['Vegan','Proteinreich'],
    ],
    'ingredients' => [
      ['qty'=>250, 'unit'=>'g', 'item'=>'Rote Linsen'],
      ['qty'=>1, 'unit'=>'Stk', 'item'=>'Zwiebel'],
      ['qty'=>1, 'unit'=>'EL', 'item'=>'Currypaste'],
      ['qty'=>400, 'unit'=>'ml', 'item'=>'Kokosmilch'],
      ['qty'=>400, 'unit'=>'ml', 'item'=>'Gemüsebrühe'],
      ['qty'=>1, 'unit'=>'Prise', 'item'=>'Salz'],
    ],
    'steps' => [
      'Zwiebel anschwitzen, Currypaste kurz mitrösten.',
      'Linsen, Brühe und Kokosmilch zugeben, 15–20 Minuten köcheln.',
      'Mit Salz abschmecken.',
    ],
    'image_url' => 'https://picsum.photos/400/300?random=15',
  ];
}

function getExampleRecipe6(): array {
  return [
    'id' => 6,
    'user' => 'susi',
    'title' => 'Omas Apfelkuchen',
    'description' => 'Klassischer, saftiger Apfelkuchen.',
    'time_minutes' => 75,
    'servings' => 8,
    'tags' => [
      'course' => ['Dessert'],
      'specials' => ['Vegetarisch'],
    ],
    'ingredients' => [
      ['qty'=>250, 'unit'=>'g', 'item'=>'Mehl'],
      ['qty'=>150, 'unit'=>'g', 'item'=>'Zucker'],
      ['qty'=>120, 'unit'=>'g', 'item'=>'Butter'],
      ['qty'=>2,   'unit'=>'Stk','item'=>'Eier'],
      ['qty'=>4,   'unit'=>'Stk','item'=>'Äpfel'],
      ['qty'=>1,   'unit'=>'TL', 'item'=>'Zimt'],
      ['qty'=>1,   'unit'=>'TL', 'item'=>'Backpulver'],
    ],
    'steps' => [
      'Teig aus Mehl, Zucker, Butter, Eiern und Backpulver zubereiten.',
      'Äpfel schälen, in Spalten schneiden und mit Zimt mischen.',
      'Teig in Form füllen, Äpfel darauf verteilen, bei 180°C 45–55 Min. backen.',
    ],
    // leer lassen -> Platzhalter-Bild
    'image_url' => '',
  ];
}

function getExampleRecipe7(): array {
  return [
    'id' => 7, 'user' => 'max',
    'title' => 'Griechischer Salat',
    'description' => 'Frischer Salat mit Feta, Gurke, Tomaten und Oliven.',
    'time_minutes' => 15, 'servings' => 2,
    'tags' => ['course'=>['Salat'],'cuisine'=>['Mediterran'],'level'=>['Einfach']],
    'ingredients' => [
      ['qty'=>2,'unit'=>'Stk','item'=>'Tomaten'], ['qty'=>1,'unit'=>'Stk','item'=>'Gurke'],
      ['qty'=>100,'unit'=>'g','item'=>'Feta'], ['qty'=>50,'unit'=>'g','item'=>'Oliven'],
      ['qty'=>2,'unit'=>'EL','item'=>'Olivenöl'], ['qty'=>1,'unit'=>'EL','item'=>'Essig'], ['qty'=>1,'unit'=>'Prise','item'=>'Salz & Pfeffer'],
    ],
    'steps' => ['Gemüse schneiden.', 'Alles mit Öl und Essig mischen.', 'Mit Salz & Pfeffer abschmecken.'],
    'image_url' => 'https://picsum.photos/400/300?random=16',
  ];
}

function getExampleRecipe8(): array {
  return [
    'id' => 8, 'user' => 'max',
    'title' => 'Chicken Curry',
    'description' => 'Mildes Hähnchen-Curry mit Kokosmilch.',
    'time_minutes' => 40, 'servings' => 4,
    'tags' => ['cuisine'=>['Indisch'],'course'=>['Hauptgericht'],'level'=>['Mittel']],
    'ingredients' => [
      ['qty'=>400,'unit'=>'g','item'=>'Hähnchenbrust'], ['qty'=>1,'unit'=>'Stk','item'=>'Zwiebel'],
      ['qty'=>2,'unit'=>'EL','item'=>'Currypaste'], ['qty'=>400,'unit'=>'ml','item'=>'Kokosmilch'],
      ['qty'=>200,'unit'=>'ml','item'=>'Brühe'], ['qty'=>1,'unit'=>'Prise','item'=>'Salz'],
    ],
    'steps' => ['Zwiebel anschwitzen.', 'Huhn anbraten.', 'Currypaste kurz mitrösten.', 'Mit Kokosmilch & Brühe köcheln.'],
    'image_url' => 'https://picsum.photos/400/300?random=17',
  ];
}

function getExampleRecipe9(): array {
  return [
    'id' => 9, 'user' => 'anna',
    'title' => 'Caprese Sandwich',
    'description' => 'Mozzarella, Tomate, Basilikum im Ciabatta.',
    'time_minutes' => 10, 'servings' => 2,
    'tags' => ['meal'=>['Mittagessen'],'cuisine'=>['Italienisch'],'specials'=>['Vegetarisch']],
    'ingredients' => [
      ['qty'=>2,'unit'=>'Stk','item'=>'Ciabatta Brötchen'], ['qty'=>125,'unit'=>'g','item'=>'Mozzarella'],
      ['qty'=>2,'unit'=>'Stk','item'=>'Tomaten'], ['qty'=>1,'unit'=>'Handvoll','item'=>'Basilikum'], ['qty'=>1,'unit'=>'EL','item'=>'Olivenöl'],
    ],
    'steps' => ['Brötchen aufschneiden.', 'Mit Tomaten, Mozzarella, Basilikum belegen.', 'Mit Olivenöl beträufeln.'],
    'image_url' => 'https://picsum.photos/400/300?random=18',
  ];
}

function getExampleRecipe10(): array {
  return [
    'id' => 10, 'user' => 'susi',
    'title' => 'Kartoffelgratin',
    'description' => 'Cremiges Gratin mit Käsekruste.',
    'time_minutes' => 60, 'servings' => 4,
    'tags' => ['course'=>['Beilage'],'cuisine'=>['Französisch'],'level'=>['Mittel']],
    'ingredients' => [
      ['qty'=>800,'unit'=>'g','item'=>'Kartoffeln'], ['qty'=>200,'unit'=>'ml','item'=>'Sahne'],
      ['qty'=>200,'unit'=>'ml','item'=>'Milch'], ['qty'=>100,'unit'=>'g','item'=>'Käse'],
      ['qty'=>1,'unit'=>'Prise','item'=>'Muskat'], ['qty'=>1,'unit'=>'Prise','item'=>'Salz & Pfeffer'],
    ],
    'steps' => ['Kartoffeln hobeln.', 'Mit Sahne/Milch würzen.', 'Schichten, Käse oben drauf.', 'Backen bis goldbraun.'],
    'image_url' => 'https://picsum.photos/400/300?random=19',
  ];
}

function getExampleRecipe11(): array {
  return [
    'id' => 11, 'user' => 'max',
    'title' => 'Shakshuka',
    'description' => 'Eier in würziger Tomatensauce.',
    'time_minutes' => 30, 'servings' => 3,
    'tags' => ['meal'=>['Frühstück'],'cuisine'=>['Orientalisch'],'specials'=>['Vegetarisch']],
    'ingredients' => [
      ['qty'=>1,'unit'=>'Stk','item'=>'Zwiebel'], ['qty'=>2,'unit'=>'Stk','item'=>'Knoblauch'],
      ['qty'=>1,'unit'=>'Stk','item'=>'Paprika'], ['qty'=>1,'unit'=>'Dose','item'=>'Tomaten'],
      ['qty'=>4,'unit'=>'Stk','item'=>'Eier'], ['qty'=>1,'unit'=>'TL','item'=>'Paprikapulver'], ['qty'=>1,'unit'=>'Prise','item'=>'Salz & Pfeffer'],
    ],
    'steps' => ['Gemüse anschwitzen.', 'Tomaten köcheln.', 'Eier setzen und stocken lassen.'],
    'image_url' => 'https://picsum.photos/400/300?random=20',
  ];
}

function getExampleRecipe12(): array {
  return [
    'id' => 12, 'user' => 'anna',
    'title' => 'Lachs aus dem Ofen',
    'description' => 'Saftiger Lachs mit Zitronenbutter.',
    'time_minutes' => 25, 'servings' => 2,
    'tags' => ['course'=>['Hauptgericht'],'level'=>['Einfach'],'specials'=>['Proteinreich']],
    'ingredients' => [
      ['qty'=>2,'unit'=>'Stk','item'=>'Lachsfilets'], ['qty'=>1,'unit'=>'Stk','item'=>'Zitrone'],
      ['qty'=>2,'unit'=>'EL','item'=>'Butter'], ['qty'=>1,'unit'=>'Prise','item'=>'Salz & Pfeffer'],
    ],
    'steps' => ['Butter mit Zitrone schmelzen.', 'Lachs würzen, mit Butter übergießen.', 'Bei 180°C 12–15 Min. backen.'],
    'image_url' => 'https://picsum.photos/400/300?random=21',
  ];
}

function getExampleRecipe13(): array {
  return [
    'id' => 13, 'user' => 'susi',
    'title' => 'Veggie Burrito',
    'description' => 'Bohnen, Reis, Gemüse in Tortilla.',
    'time_minutes' => 30, 'servings' => 4,
    'tags' => ['cuisine'=>['Mexikanisch'],'specials'=>['Vegetarisch']],
    'ingredients' => [
      ['qty'=>4,'unit'=>'Stk','item'=>'Tortillas'], ['qty'=>200,'unit'=>'g','item'=>'Reis, gekocht'],
      ['qty'=>200,'unit'=>'g','item'=>'Bohnen'], ['qty'=>1,'unit'=>'Stk','item'=>'Paprika'], ['qty'=>1,'unit'=>'Stk','item'=>'Avocado'],
    ],
    'steps' => ['Füllung mischen.', 'In Tortillas wickeln.', 'Kurz anrösten oder direkt servieren.'],
    'image_url' => 'https://picsum.photos/400/300?random=22',
  ];
}

function getExampleRecipe14(): array {
  return [
    'id' => 14, 'user' => 'max',
    'title' => 'Pilz-Risotto',
    'description' => 'Cremiges Risotto mit Champignons.',
    'time_minutes' => 35, 'servings' => 4,
    'tags' => ['cuisine'=>['Italienisch'],'level'=>['Mittel']],
    'ingredients' => [
      ['qty'=>300,'unit'=>'g','item'=>'Risotto-Reis'], ['qty'=>200,'unit'=>'g','item'=>'Champignons'],
      ['qty'=>1,'unit'=>'Stk','item'=>'Zwiebel'], ['qty'=>100,'unit'=>'ml','item'=>'Wein'],
      ['qty'=>800,'unit'=>'ml','item'=>'Brühe'], ['qty'=>50,'unit'=>'g','item'=>'Parmesan'],
    ],
    'steps' => ['Zwiebel & Pilze anschwitzen.', 'Reis glasig, mit Wein ablöschen.', 'Mit Brühe rühren bis cremig.'],
    'image_url' => 'https://picsum.photos/400/300?random=23',
  ];
}

function getExampleRecipe15(): array {
  return [
    'id' => 15, 'user' => 'anna',
    'title' => 'Tomaten-Mozzarella Salat',
    'description' => 'Klassischer Caprese-Salat.',
    'time_minutes' => 10, 'servings' => 2,
    'tags' => ['course'=>['Salat'],'cuisine'=>['Italienisch'],'level'=>['Einfach']],
    'ingredients' => [
      ['qty'=>2,'unit'=>'Stk','item'=>'Tomaten'], ['qty'=>125,'unit'=>'g','item'=>'Mozzarella'],
      ['qty'=>1,'unit'=>'Handvoll','item'=>'Basilikum'], ['qty'=>1,'unit'=>'EL','item'=>'Olivenöl'], ['qty'=>1,'unit'=>'Prise','item'=>'Salz'],
    ],
    'steps' => ['Scheiben schneiden.', 'Anrichten und würzen.'],
    'image_url' => 'https://picsum.photos/400/300?random=24',
  ];
}

function getExampleRecipe16(): array {
  return [
    'id' => 16, 'user' => 'susi',
    'title' => 'Ofengemüse',
    'description' => 'Buntes Gemüse aus dem Ofen.',
    'time_minutes' => 45, 'servings' => 4,
    'tags' => ['course'=>['Beilage'],'specials'=>['Vegan']],
    'ingredients' => [
      ['qty'=>1,'unit'=>'Stk','item'=>'Zucchini'], ['qty'=>1,'unit'=>'Stk','item'=>'Paprika'],
      ['qty'=>1,'unit'=>'Stk','item'=>'Karotte'], ['qty'=>2,'unit'=>'EL','item'=>'Olivenöl'], ['qty'=>1,'unit'=>'Prise','item'=>'Salz & Pfeffer'],
    ],
    'steps' => ['Gemüse schneiden.', 'Mit Öl und Gewürzen mischen.', 'Bei 200°C 25–30 Min. backen.'],
    'image_url' => 'https://picsum.photos/400/300?random=25',
  ];
}

function getExampleRecipe17(): array {
  return [
    'id' => 17, 'user' => 'max',
    'title' => 'Caesar Salad',
    'description' => 'Klassischer Caesar mit Dressing.',
    'time_minutes' => 20, 'servings' => 2,
    'tags' => ['course'=>['Salat'],'level'=>['Mittel']],
    'ingredients' => [
      ['qty'=>1,'unit'=>'Stk','item'=>'Römersalat'], ['qty'=>50,'unit'=>'g','item'=>'Parmesan'],
      ['qty'=>1,'unit'=>'Stk','item'=>'Hähnchenbrust'], ['qty'=>2,'unit'=>'Stk','item'=>'Toastbrot'],
    ],
    'steps' => ['Salat und Hähnchen zubereiten.', 'Croutons rösten.', 'Mit Dressing mischen.'],
    'image_url' => 'https://picsum.photos/400/300?random=26',
  ];
}

function getExampleRecipe18(): array {
  return [
    'id' => 18, 'user' => 'anna',
    'title' => 'Spinat-Feta Quiche',
    'description' => 'Herzhafte Quiche mit Spinat.',
    'time_minutes' => 50, 'servings' => 6,
    'tags' => ['course'=>['Hauptgericht'],'specials'=>['Vegetarisch']],
    'ingredients' => [
      ['qty'=>1,'unit'=>'Stk','item'=>'Mürbeteig'], ['qty'=>300,'unit'=>'g','item'=>'Spinat'],
      ['qty'=>150,'unit'=>'g','item'=>'Feta'], ['qty'=>3,'unit'=>'Stk','item'=>'Eier'], ['qty'=>200,'unit'=>'ml','item'=>'Sahne'],
    ],
    'steps' => ['Teig blindbacken.', 'Füllung mischen.', 'Backen bis gestockt.'],
    'image_url' => 'https://picsum.photos/400/300?random=27',
  ];
}

function getExampleRecipe19(): array {
  return [
    'id' => 19, 'user' => 'susi',
    'title' => 'Ratatouille',
    'description' => 'Provenzalisches Schmorgemüse.',
    'time_minutes' => 60, 'servings' => 4,
    'tags' => ['cuisine'=>['Französisch'],'specials'=>['Vegan']],
    'ingredients' => [
      ['qty'=>1,'unit'=>'Stk','item'=>'Aubergine'], ['qty'=>1,'unit'=>'Stk','item'=>'Zucchini'],
      ['qty'=>1,'unit'=>'Stk','item'=>'Paprika'], ['qty'=>1,'unit'=>'Stk','item'=>'Zwiebel'], ['qty'=>1,'unit'=>'Dose','item'=>'Tomaten'],
    ],
    'steps' => ['Gemüse schneiden.', 'Nacheinander anbraten.', 'Mit Tomaten schmoren.'],
    'image_url' => 'https://picsum.photos/400/300?random=28',
  ];
}

function getExampleRecipe20(): array {
  return [
    'id' => 20, 'user' => 'max',
    'title' => 'BBQ Pulled Pork Sandwich',
    'description' => 'Zartes Pulled Pork mit BBQ-Sauce.',
    'time_minutes' => 240, 'servings' => 6,
    'tags' => ['course'=>['Hauptgericht'],'level'=>['Anspruchsvoll'],'specials'=>['Proteinreich']],
    'ingredients' => [
      ['qty'=>1,'unit'=>'kg','item'=>'Schweineschulter'], ['qty'=>200,'unit'=>'ml','item'=>'BBQ-Sauce'],
      ['qty'=>6,'unit'=>'Stk','item'=>'Burger-Buns'], ['qty'=>1,'unit'=>'Prise','item'=>'Salz & Pfeffer'],
    ],
    'steps' => ['Fleisch würzen.', 'Langsam schmoren/backen.', 'Zerfasern und mit Sauce mischen.'],
    'image_url' => 'https://picsum.photos/400/300?random=29',
  ];
}

function getExampleRecipe21(): array {
  return [
    'id' => 21, 'user' => 'anna',
    'title' => 'Bananenbrot',
    'description' => 'Saftiges Bananenbrot – ideal zum Kaffee.',
    'time_minutes' => 65, 'servings' => 10,
    'tags' => ['course'=>['Dessert'],'level'=>['Einfach']],
    'ingredients' => [
      ['qty'=>3,'unit'=>'Stk','item'=>'reife Bananen'], ['qty'=>250,'unit'=>'g','item'=>'Mehl'],
      ['qty'=>120,'unit'=>'g','item'=>'Zucker'], ['qty'=>2,'unit'=>'Stk','item'=>'Eier'], ['qty'=>1,'unit'=>'TL','item'=>'Backpulver'],
    ],
    'steps' => ['Bananen zerdrücken.', 'Mit restlichen Zutaten mischen.', 'Bei 180°C 50–60 Min. backen.'],
    // leer -> Platzhalter sichtbar
    'image_url' => '',
  ];
}

function getExampleRecipes(): array {
  return [
    getExampleRecipe1(), getExampleRecipe2(), getExampleRecipe3(), getExampleRecipe4(), getExampleRecipe5(), getExampleRecipe6(),
    getExampleRecipe7(), getExampleRecipe8(), getExampleRecipe9(), getExampleRecipe10(), getExampleRecipe11(),
    getExampleRecipe12(), getExampleRecipe13(), getExampleRecipe14(), getExampleRecipe15(), getExampleRecipe16(),
    getExampleRecipe17(), getExampleRecipe18(), getExampleRecipe19(), getExampleRecipe20(), getExampleRecipe21(),
  ];
}