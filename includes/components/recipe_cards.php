<?php

function displayRecipeCard(array $recipes, bool $manage = false): void {

// Erwartete Struktur von $recipes:
//
// $recipes = [
//     RECIPE_ID => [
//         'id'           => int,
//         'title'        => string,
//         'description'  => string|null,
//         'time_min'     => int|null,
//         'servings'     => int|null,
//         'steps'        => string|null,
//         'picture_path' => string,
//         'user_id'      => int|null,
//
//         'tags' => [
//             [
//                 'id'       => int,
//                 'name'     => string,
//                 'category' => string
//             ],
//             // ... beliebig viele Tags
//         ]
//     ],
//     // weiteres Rezept …
// ];


    foreach ($recipes as $recipe) { ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <article class="card h-100 shadow-sm">
                <img
                    class="card-img-top"
                    src="<?= esc(BASE_URL . $recipe['picture_path']) ?>"
                    onerror="this.onerror=null;this.src='<?= esc(BASE_URL) ?>/img/placeholder_food.jpg';"
                    alt="Rezeptbild"
                >
                <div class="card-body d-flex flex-column">

                    <h3 class="h6 mb-2"><?= esc($recipe['title']) ?></h3>

                    <?php if (!empty($recipe['tags'])): ?>
                        <div class="d-flex flex-wrap gap-1 mb-2">
                            <?php foreach ($recipe['tags'] as $tag): ?>
                                <span class="badge" style="font-size:0.7rem;"><?= esc($tag['name']) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($recipe['description'])): ?>
                        <p class="text-muted small mb-2"><?= esc($recipe['description']) ?></p>
                    <?php endif; ?>

                    <div class="small text-muted mb-3">
                        <?php if (!empty($recipe['time_min']) && (int)$recipe['time_min'] > 0): ?>
                            <span>⏱ <?= esc((string)$recipe['time_min']) ?> min</span>
                        <?php endif; ?>
                        <?php if (!empty($recipe['servings']) && (int)$recipe['servings'] > 0): ?>
                            <span class="ms-2">👥 <?= esc((string)$recipe['servings']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="mt-auto d-flex gap-2 flex-wrap">
                        <a href="recipe.php?id=<?= (int)$recipe['id'] ?>" class="btn btn-outline-secondary btn-sm">Ansehen</a>

                        <?php if ($manage): ?>
                            <a href="user_recipe_edit.php?id=<?= (int)$recipe['id'] ?>" class="btn btn-outline-primary btn-sm">Bearbeiten</a>


                            <form method="post" action="recipe_delete.php" class="d-inline">
                                <input type="hidden" name="id" value="<?= (int)$recipe['id'] ?>">
                                <button
                                    type="submit"
                                    class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Rezept wirklich löschen?');"
                                >
                                    Löschen
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                </div>
            </article>
        </div>
    <?php }
}

function displayCompactRecipeCards(array $recipes): void
{
    foreach ($recipes as $recipe) { ?>
        <div class="col-12">
            <article class="card h-100 shadow-sm">

                <img
                    class="card-img-top"
                    src="<?= esc(BASE_URL . $recipe['picture_path']) ?>"
                    onerror="this.onerror=null;this.src='<?= esc(BASE_URL) ?>/img/placeholder_food.jpg';"
                    alt="Rezeptbild"
                >

                <div class="card-body d-flex flex-column">

                    <h3 class="h6 mb-2"><?= esc($recipe['title']) ?></h3>

                    <?php if (!empty($recipe['tags'])): ?>
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <?php foreach (array_slice($recipe['tags'], 0, 4) as $tag): ?>
                                <span class="badge" style="font-size:0.7rem;">
                                    <?= esc($tag['name']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mt-auto">
                        <a href="recipe.php?id=<?= (int)$recipe['id'] ?>" class="btn btn-outline-secondary btn-sm">Ansehen</a>
                    </div>

                </div>
            </article>
        </div>
    <?php }
}

?>