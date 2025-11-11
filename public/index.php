<?php
$pageTitle = 'Homepage';
// zum Testen kannst du hier 'guest' | 'user' | 'admin' setzen:
$role = 'admin';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';
?>
<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Willkommen in deinem persönlichen Kochbuch</h1>
    <p class="text-muted">
      Entdecke neue Rezepte, lass dich inspirieren und verwalte deine Lieblingsgerichte ganz einfach online.
    </p>
  </section>

  <!-- Rezept der Woche -->
  <section class="bg-cream section mb-3 mb-md-4">      
    <div class="row g-3 align-items-center">
      <div class="col-12 col-md-6">
        <h3 class="h4 mb-1 text-muted">Rezept der Woche</h3>
        <h5 class="h5 mb-2">Spaghetti Aglio e Olio</h5>
        <span class="badge me-1">Abendessen</span>
        <span class="badge me-1">Italienisch</span>
        <span class="badge me-1">Einfach</span>             
        <p class="mb-3 text-muted">
          Aromatische Spaghetti mit Knoblauch, Chili und Olivenöl – ein echter italienischer Klassiker.
        </p>
        <a href="recipe.php?id=1" class="btn btn-primary btn-sm">Zum Rezept</a>
      </div>

      <div class="col-12 col-md-6 order-md-2 text-center">
        <a href="recipe.php?id=1">
          <img src="https://picsum.photos/400/300?random=11"
               onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
               alt="Spaghetti Aglio e Olio" class="card-img-top rounded">
        </a>
      </div>
    </div>
  </section>

  <!-- Am besten bewertet -->
  <section class="bg-cream section mb-3 mb-md-4">      
    <div class="row g-3 align-items-center">
      <div class="col-12 col-md-6 text-center">
        <a href="recipe.php?id=2">
          <img src="https://picsum.photos/400/300?random=13"
               onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
               alt="Pesto Genovese" class="card-img-top rounded">
        </a>
      </div>

      <div class="col-12 col-md-6 order-md-2">
        <h3 class="h4 mb-1 text-muted">Am besten bewertet</h3>
        <h5 class="h5 mb-2">Pesto Genovese</h5>
        <span class="badge me-1">Vegetarisch</span>
        <span class="badge me-1">Italienisch</span> 
        <span class="badge me-1">Schnelle Küche</span>
        <p class="mb-3 text-muted">
          Frisch, aromatisch und cremig – unser beliebtestes Rezept mit Basilikum, Pinienkernen und Parmesan.
        </p>
        <a href="recipe.php?id=2" class="btn btn-primary btn-sm">Zum Rezept</a>              
      </div>
    </div>
  </section>

  <!-- Neu hinzugefügt -->
  <section class="bg-cream section mb-3 mb-md-4">      
    <div class="row g-3 align-items-center">
      <div class="col-12 col-md-6">
        <h3 class="h4 mb-1 text-muted">Neu hinzugefügt</h3>
        <h5 class="h5 mb-2">Shakshuka</h5>
        <span class="badge me-1">Frühstück</span>
        <span class="badge me-1">Orientalisch</span> 
        <span class="badge me-1">Vegetarisch</span>
        <p class="mb-3 text-muted">
          Würzige Tomaten-Paprika-Pfanne mit pochierten Eiern – ein beliebter Klassiker aus dem Nahen Osten.
        </p>
        <a href="recipe.php?id=3" class="btn btn-primary btn-sm">Zum Rezept</a>
      </div>

      <div class="col-12 col-md-6 order-md-2 text-center">
        <a href="recipe.php?id=3">
          <img src="https://picsum.photos/400/300?random=14"
               onerror="this.onerror=null;this.src='img/placeholder_food.jpg';"
               alt="Shakshuka" class="card-img-top rounded">
        </a>
      </div>
    </div>
  </section>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
