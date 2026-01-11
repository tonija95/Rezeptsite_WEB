<?php
$pageTitle = 'Rezept bearbeiten';
$role = 'user';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/nav.php';

require_once __DIR__ . '/../includes/user_recipe_edit_logic.php';
?>

<main>
<div class="container">

    <section class="hero section my-3 my-md-4">
        <h1 class="h3 mb-2"><?= esc($pageTitle) ?></h1>
        <p class="text-muted">
            <?= ($recipeId !== null) ? 'Bearbeite dein Rezept.' : 'Lege ein neues Rezept an.' ?>
        </p>
    </section>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="m-0 ps-3">
                <?php foreach ($errors as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <section class="section bg-cream mb-3 mb-md-4 py-3 px-3">

        <form method="post" class="row g-3" enctype="multipart/form-data">


            <div class="col-12 col-lg-8">

                <label class="form-label">Rezeptname *</label>
                <input
                    type="text"
                    name="title"
                    class="form-control"
                    required
                    value="<?= esc($form['title']) ?>"
                >

                <div class="mt-3">
                    <label class="form-label">Kurzbeschreibung</label>
                    <textarea
                        name="description"
                        rows="3"
                        class="form-control"
                    ><?= esc($form['description']) ?></textarea>
                </div>

                <div class="mt-3">
                    <label class="form-label d-block">Zutaten</label>

                    <div class="d-flex flex-column gap-2">
                        <?php
                        $rows = !empty($form['ingredients'])
                            ? array_values($form['ingredients'])
                            : [['quantity' => '', 'unit' => '', 'name' => '']];
                        ?>

                        <?php foreach ($rows as $i => $ing): ?>
                            <div class="row g-2 align-items-center">
                                <div class="col-3">
                                    <input
                                        type="text"
                                        name="ingredients[quantity][]"
                                        class="form-control"
                                        placeholder="Menge"
                                        value="<?= esc($ing['quantity'] ?? '') ?>"
                                    >
                                </div>

                                <div class="col-3">
                                    <select name="ingredients[unit][]" class="form-select">
                                        <option value=""></option>
                                        <?php foreach ($units as $u): ?>
                                            <option
                                                value="<?= esc($u) ?>"
                                                <?= (($ing['unit'] ?? '') === $u) ? 'selected' : '' ?>
                                            >
                                                <?= esc($u) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-5">
                                    <input
                                        type="text"
                                        name="ingredients[name][]"
                                        class="form-control"
                                        placeholder="Zutat"
                                        value="<?= esc($ing['name'] ?? '') ?>"
                                    >
                                </div>

                                <div class="col-1 d-grid">
                                    <button
                                        type="submit"
                                        name="remove_ing"
                                        value="<?= (int)$i ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Zutat entfernen"
                                    >
                                        ×
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button
                        type="submit"
                        name="add_ing"
                        value="1"
                        class="btn btn-sm btn-outline-secondary mt-2"
                    >
                        Zutat hinzufügen
                    </button>
                </div>

                <div class="mt-3">
                    <label class="form-label">Zubereitungsschritte (je Zeile ein Schritt)</label>
                    <textarea
                        name="steps"
                        rows="6"
                        class="form-control"
                        placeholder="Schritt 1&#10;Schritt 2&#10;Schritt 3"
                    ><?= esc($form['steps']) ?></textarea>
                </div>

            </div>


            <div class="col-12 col-lg-4">
                <div class="row g-3">

                    <div class="col-6">
                        <label class="form-label">Dauer (min)</label>
                        <input
                            type="number"
                            name="time_min"
                            class="form-control"
                            min="0"
                            value="<?= esc($form['time_min']) ?>"
                        >
                    </div>

                    <div class="col-6">
                        <label class="form-label">Portionen</label>
                        <input
                            type="number"
                            name="servings"
                            class="form-control"
                            min="0"
                            value="<?= esc($form['servings']) ?>"
                        >
                    </div>


                    <div class="col-12">
                        <label for="recipeImage" class="form-label">Bild hochladen</label>

                        <?php
                        $currentImg = trim((string)($form['picture_path'] ?? ''));
                        if ($currentImg === '') {
                            $currentImg = '/img/placeholder_food.jpg';
                        }
                        ?>

                        <div class="mb-2">
                            <img
                                src="<?= esc($currentImg) ?>"
                                onerror="this.onerror=null;this.src='/img/placeholder_food.jpg';"
                                alt="Aktuelles Rezeptbild"
                                class="img-fluid rounded"
                                style="max-height: 180px; object-fit: cover;"
                            >
                        </div>

                        <input type="hidden" 
                        name="current_picture_path" 
                        value="<?= esc($form['picture_path'] ?? '/img/placeholder_food.jpg') ?>"
                        >

                        <input
                            type="file"
                            id="recipeImage"
                            name="image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp"
                        >

                        <div class="form-text">
                            Erlaubt: JPG, PNG, WEBP (max. 2&nbsp;MB)
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label d-block mb-2">Tags</label>

                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($groupedTags as $category => $tagsInCat): ?>
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                    >
                                        <?= esc($category) ?>
                                    </button>

                                    <ul class="dropdown-menu p-2" style="min-width: 220px;">
                                        <?php foreach ($tagsInCat as $tag): ?>
                                            <?php $isChecked = in_array((int)$tag['id'], $form['tag_ids'], true); ?>
                                            <li>
                                                <label class="form-check small">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="<?= esc($category) ?>[]"
                                                        value="<?= (int)$tag['id'] ?>"
                                                        <?= $isChecked ? 'checked' : '' ?>
                                                    >
                                                    <span class="form-check-label">
                                                        <?= esc($tag['name']) ?>
                                                    </span>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <?= ($recipeId !== null) ? 'Speichern' : 'Erstellen' ?>
                        </button>
                        <a href="recipes.php" class="btn btn-outline-secondary">Abbrechen</a>
                    </div>

                </div>
            </div>

        </form>
    </section>

</div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
