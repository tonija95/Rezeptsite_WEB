<?php

require_once __DIR__ . '/db_connect.php';

function getUserByUsername(string $username): ?array
{
    $db = getDbConnection();

    $sql = "SELECT id, name, password, role, email FROM user WHERE name = ? LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    return $user ?: null;
}

function getAllUsers(): array
{
    $db = getDbConnection();

    $sql = "SELECT id, name, email, role FROM user";
    $res = $db->query($sql);

    $users = [];
    while ($row = $res->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}

function getAllTags(): array
{
    $db = getDbConnection();

    $sql = "SELECT * FROM tags";
    $res = $db->query($sql);

    $tags = [];
    while ($row = $res->fetch_assoc()) {
        $tags[] = $row;
    }

    return $tags;
}

function getAllRecipesWithTags(): array
{
    $db = getDbConnection();

    $sql = "
        SELECT 
            r.id           AS recipe_id,
            r.title        AS recipe_title,
            r.description  AS recipe_description,
            r.time_min     AS recipe_time_min,
            r.servings     AS recipe_servings,
            r.steps        AS recipe_steps,
            r.user_id      AS recipe_user_id,
            r.picture_path AS recipe_picture_path,
            t.id           AS tag_id,
            t.name         AS tag_name,
            t.category     AS tag_category
        FROM recipes r
        LEFT JOIN recipe_tags rt ON rt.recipe_id = r.id
        LEFT JOIN tags t         ON t.id = rt.tag_id
    ";

    $res = $db->query($sql);

    $recipes = [];

    while ($row = $res->fetch_assoc()) {
        $rid = (int)$row['recipe_id'];

        if (!isset($recipes[$rid])) {
            $recipes[$rid] = [
                'id'           => $rid,
                'title'        => $row['recipe_title'],
                'description'  => $row['recipe_description'],
                'time_min'     => $row['recipe_time_min'],
                'servings'     => $row['recipe_servings'],
                'steps'        => $row['recipe_steps'],
                'user_id'      => $row['recipe_user_id'],
                'picture_path' => $row['recipe_picture_path'],
                'tags'         => [],
            ];
        }

        if ($row['tag_id'] !== null) {
            $recipes[$rid]['tags'][] = [
                'id'       => (int)$row['tag_id'],
                'name'     => $row['tag_name'],
                'category' => $row['tag_category'],
            ];
        }
    }

    return $recipes;
}

function getRecipeIdsByUserId(int $userId): array
{
    $db = getDbConnection();

    $sql = "SELECT id FROM recipes WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = (int)$row['id'];
    }

    return $ids;
}

function getRecipesWithTags(array $recipeIds): ?array
{
    if (empty($recipeIds)) {
        return null;
    }

    $db = getDbConnection();

    $recipeIds = array_values(array_map('intval', $recipeIds));
    $placeholders = implode(',', array_fill(0, count($recipeIds), '?'));

    $sql = "
        SELECT 
            r.id           AS recipe_id,
            r.title,
            r.description,
            r.time_min,
            r.servings,
            r.steps,
            r.user_id,
            r.picture_path,
            t.id       AS tag_id,
            t.name     AS tag_name,
            t.category AS tag_category
        FROM recipes r
        LEFT JOIN recipe_tags rt ON rt.recipe_id = r.id
        LEFT JOIN tags t         ON t.id = rt.tag_id
        WHERE r.id IN ($placeholders)
    ";

    $stmt = $db->prepare($sql);
    $types = str_repeat('i', count($recipeIds));
    $stmt->bind_param($types, ...$recipeIds);
    $stmt->execute();

    $result = $stmt->get_result();

    $recipes = [];

    while ($row = $result->fetch_assoc()) {
        $rid = (int)$row['recipe_id'];

        if (!isset($recipes[$rid])) {
            $recipes[$rid] = [
                'id'           => $rid,
                'title'        => $row['title'],
                'description'  => $row['description'],
                'time_min'     => $row['time_min'],
                'servings'     => $row['servings'],
                'steps'        => $row['steps'],
                'user_id'      => $row['user_id'],
                'picture_path' => $row['picture_path'],
                'tags'         => [],
            ];
        }

        if ($row['tag_id'] !== null) {
            $recipes[$rid]['tags'][] = [
                'id'       => (int)$row['tag_id'],
                'name'     => $row['tag_name'],
                'category' => $row['tag_category'],
            ];
        }
    }

    return $recipes;
}

function getFavoriteRecipeIdsByUserId(int $userId): array
{
    $db = getDbConnection();

    $sql = "SELECT recipe_id FROM user_favorites WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $res = $stmt->get_result();

    $ids = [];
    while ($row = $res->fetch_assoc()) {
        $ids[] = (int)$row['recipe_id'];
    }

    return $ids;
}

function isRecipeFavorited(int $userId, int $recipeId): bool
{
    $db = getDbConnection();

    $sql = "SELECT 1 FROM user_favorites WHERE user_id = ? AND recipe_id = ? LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $userId, $recipeId);
    $stmt->execute();

    $res = $stmt->get_result();
    return $res && $res->num_rows > 0;
}

function getRecipeIdsByTagFilters(array $filters): array
{
    $db = getDbConnection();

    $whereParts = [];
    $params = [];
    $types = "";

    foreach ($filters as $category => $tagIds) {
        if (!is_array($tagIds) || empty($tagIds)) {
            continue;
        }

        $tagIds = array_values(array_map('intval', $tagIds));
        if (empty($tagIds)) {
            continue;
        }

        $placeholders = implode(',', array_fill(0, count($tagIds), '?'));
        $whereParts[] = "(t.category = ? AND t.id IN ($placeholders))";

        $types .= "s" . str_repeat("i", count($tagIds));
        $params[] = $category;

        foreach ($tagIds as $id) {
            $params[] = $id;
        }
    }

    if (empty($whereParts)) {
        return [];
    }

    $numCategories = count($whereParts);

    $sql = "
        SELECT r.id
        FROM recipes r
        JOIN recipe_tags rt ON rt.recipe_id = r.id
        JOIN tags t ON t.id = rt.tag_id
        WHERE " . implode(" OR ", $whereParts) . "
        GROUP BY r.id
        HAVING COUNT(DISTINCT t.category) = ?
    ";

    $types .= "i";
    $params[] = $numCategories;

    $stmt = $db->prepare($sql);

    $bind = [];
    $bind[] = $types;
    foreach ($params as $k => $v) {
        $bind[] = &$params[$k];
    }
    call_user_func_array([$stmt, 'bind_param'], $bind);

    $stmt->execute();
    $result = $stmt->get_result();

    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = (int)$row['id'];
    }

    return $ids;
}

function getNewestRecipeIds(int $limit = 3): array
{
    $db = getDbConnection();

    $stmt = $db->prepare("SELECT id FROM recipes ORDER BY id DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();

    $result = $stmt->get_result();

    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = (int)$row['id'];
    }

    return $ids;
}

function getRandomRecipeIds(int $limit = 3): array
{
    $db = getDbConnection();

    $stmt = $db->prepare("SELECT id FROM recipes ORDER BY RAND() LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();

    $result = $stmt->get_result();

    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = (int)$row['id'];
    }

    return $ids;
}

function getSimilarRecipeIdsByRecipeId(int $recipeId, int $limit = 3): array
{
    $db = getDbConnection();

    $sql = "
        SELECT rt2.recipe_id AS id
        FROM recipe_tags rt1
        JOIN recipe_tags rt2 ON rt1.tag_id = rt2.tag_id
        WHERE rt1.recipe_id = ?
          AND rt2.recipe_id <> ?
        GROUP BY rt2.recipe_id
        LIMIT ?
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("iii", $recipeId, $recipeId, $limit);
    $stmt->execute();

    $result = $stmt->get_result();

    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = (int)$row['id'];
    }

    return $ids;
}

function getIngredientsByRecipeId(int $recipeId): array
{
    $db = getDbConnection();

    $sql = "SELECT quantity, unit, name FROM recipe_ingredients WHERE recipe_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $recipeId);
    $stmt->execute();

    $result = $stmt->get_result();

    $ings = [];
    while ($row = $result->fetch_assoc()) {
        $ings[] = $row;
    }

    return $ings;
}

function getShoppingListByUserId(int $userId): array
{
    $db = getDbConnection();

    $sql = "
        SELECT id, name, quantity, unit
        FROM shopping_list
        WHERE user_id = ?
        ORDER BY name
    ";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    return $items;
}

