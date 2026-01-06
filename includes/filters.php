<?php

include_once __DIR__ . '/db_gets.php';

function displayFilterOptions(bool $filterUser = false): void
{
    $tags = getAllTags();
    if (empty($tags)) {
        echo "<p>Keine Tags gefunden.</p>";
        return;
    }

    $groupedTags = [];

    foreach ($tags as $tag) {
        $category = $tag['category'];
        $groupedTags[$category][] = [
            'id'   => (int)$tag['id'],
            'name' => $tag['name']
        ];
    }
    ?>

    <form method="get" class="mb-4">
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
                        <?php
                        $selected = $_GET[$category] ?? [];
                        $selected = is_array($selected) ? array_map('intval', $selected) : [];
                        ?>

                        <?php foreach ($tagsInCat as $tag): ?>
                            <li>
                                <label class="form-check small">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="<?= esc($category) ?>[]"
                                        value="<?= esc($tag['id']) ?>"
                                        <?= in_array($tag['id'], $selected, true) ? 'checked' : '' ?>
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

            <button type="submit" class="btn btn-primary btn-sm">
                Filtern
            </button>

            <?php $baseUrl = strtok($_SERVER['REQUEST_URI'], '?'); ?>

            <a href="<?= esc($baseUrl) ?>" class="btn btn-outline-secondary btn-sm">
                Zurücksetzen
            </a>

        </div>
    </form>

    <?php
}

function readFilters(): array
{
    $filters = [];

    $tags = getAllTags();
    $allowedCategories = [];
    foreach ($tags as $t) {
        $allowedCategories[$t['category']] = true;
    }

    foreach ($_GET as $category => $ids) {
        if (!isset($allowedCategories[$category])) continue;
        if (!is_array($ids)) continue;

        $cleanIds = array_values(array_filter($ids, 'is_numeric'));
        if (!empty($cleanIds)) {
            $filters[$category] = array_map('intval', $cleanIds);
        }
    }

    return $filters;
}

function getRecipesForList(array $filters): array
{
    if (empty($filters)) {
        return getAllRecipesWithTags();
    }

    $ids = getRecipeIdsByTagFilters($filters);

    if (empty($ids)) {
        return [];
    }

    return getRecipesWithTags($ids) ?? [];
}

function getIndexRecipes(): array
{
    $newestIds = getNewestRecipeIds(3);
    $newest = !empty($newestIds) ? (getRecipesWithTags($newestIds) ?? []) : [];

    $randomIds = getRandomRecipeIds(3);
    $random = !empty($randomIds) ? (getRecipesWithTags($randomIds) ?? []) : [];

    return [
        'newest' => $newest,
        'random' => $random,
    ];
}
