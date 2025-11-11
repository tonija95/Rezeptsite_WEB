<?php
$pageTitle = 'Meine Einkaufsliste';
$role = 'user';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

/* Dummy-Daten (später DB oder Session) */
$shoppingItems = [
  ['id'=>1, 'name'=>'Spaghetti',      'qty'=>'500', 'unit'=>'g'],
  ['id'=>2, 'name'=>'Tomaten',        'qty'=>'4',   'unit'=>'Stk'],
  ['id'=>3, 'name'=>'Olivenöl',       'qty'=>'2',   'unit'=>'EL'],
  ['id'=>4, 'name'=>'Knoblauchzehen', 'qty'=>'2',   'unit'=>'Stk'],
];
?>
<div class="container">

  <!-- Hero -->
  <section class="hero section my-3 my-md-4">
    <h1 class="h3 mb-2">Einkaufsliste</h1>
    <p class="text-muted">Hier kannst du deine benötigten Zutaten verwalten.</p>
  </section>

  <!-- Liste -->
  <section class="section bg-cream mb-5">
    <ul class="list-group list-group-flush" id="shopping-list">
      <?php foreach ($shoppingItems as $it): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center shopping-item" data-id="<?= (int)$it['id'] ?>">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span><?= htmlspecialchars(trim(($it['qty'] ?? '') . ' ' . ($it['unit'] ?? ''))) ?></span>
            <span><?= htmlspecialchars($it['name']) ?></span>
          </div>
          <button type="button" class="btn btn-sm btn-outline-danger remove-item" title="Eintrag löschen">✖</button>
        </li>
      <?php endforeach; ?>
    </ul>

    <div id="empty-hint" class="text-muted small mt-3" style="display:none;">
      Deine Einkaufsliste ist leer.
    </div>

    <div class="mt-3 d-flex gap-2">
      <a href="user_dashboard.php" class="btn btn-outline-secondary">Zurück</a>
    </div>
  </section>

</div>

<!-- JS: einfache Löschfunktion -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const list = document.getElementById('shopping-list');
  const emptyHint = document.getElementById('empty-hint');

  const checkEmpty = () => {
    if (list.querySelectorAll('.shopping-item').length === 0) {
      emptyHint.style.display = 'block';
    }
  };

  list.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-item')) {
      e.target.closest('.shopping-item')?.remove();
      checkEmpty();
    }
  });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
