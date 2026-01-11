<?php

require_once __DIR__ . '/db_connect.php';

function createUser(string $name, string $email, string $password): int
{
    $db = getDbConnection();

    $role = 'user';

    $sql = "INSERT INTO user (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();

    return (int)$db->insert_id;
}

function updateUserPassword(int $userId, string $hashedPassword): bool
{
    $db = getDbConnection();

    $stmt = $db->prepare("UPDATE user SET password = ? WHERE id = ? LIMIT 1");
    $stmt->bind_param("si", $hashedPassword, $userId);

    return $stmt->execute();
}

function deleteUserById(int $userIdToDelete, int $currentAdminId): bool
{
    $db = getDbConnection();

    if ($userIdToDelete === $currentAdminId) {
        return false;
    }

    $stmt = $db->prepare("SELECT role FROM user WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $userIdToDelete);
    $stmt->execute();

    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    if (!$row) {
        return false;
    }

    if ((string)$row['role'] === 'admin') {
        $res2 = $db->query("SELECT COUNT(*) AS cnt FROM user WHERE role = 'admin'");
        $cntRow = $res2 ? $res2->fetch_assoc() : null;
        $adminCount = $cntRow ? (int)$cntRow['cnt'] : 0;

        if ($adminCount <= 1) {
            return false;
        }
    }

    $stmtDel = $db->prepare("DELETE FROM user WHERE id = ? LIMIT 1");
    $stmtDel->bind_param("i", $userIdToDelete);

    return $stmtDel->execute();
}

function createRecipe(array $data, int $userId): int
{
    $db = getDbConnection();

    $title       = (string)($data['title'] ?? '');
    $description = $data['description'] ?? null;
    $timeMin     = $data['time_min'] ?? null;
    $servings    = $data['servings'] ?? null;
    $steps       = $data['steps'] ?? null;
    $picturePath = (string)($data['picture_path'] ?? '/img/placeholder_food.jpg');

    $description = ($description === '') ? null : $description;
    $steps       = ($steps === '') ? null : $steps;

    $timeMin  = (is_numeric($timeMin) && (int)$timeMin >= 0) ? (int)$timeMin : null;
    $servings = (is_numeric($servings) && (int)$servings >= 0) ? (int)$servings : null;

    $sql = "
        INSERT INTO recipes (title, description, time_min, servings, steps, user_id, picture_path)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $db->prepare($sql);

    $stmt->bind_param(
        "ssiisis",
        $title,
        $description,
        $timeMin,
        $servings,
        $steps,
        $userId,
        $picturePath
    );

    $stmt->execute();

    return (int)$db->insert_id;
}

function updateRecipe(int $recipeId, array $data, int $currentUserId, bool $isAdmin): bool
{
    $db = getDbConnection();

    $title       = (string)($data['title'] ?? '');
    $description = $data['description'] ?? null;
    $timeMin     = $data['time_min'] ?? null;
    $servings    = $data['servings'] ?? null;
    $steps       = $data['steps'] ?? null;
    $picturePath = (string)($data['picture_path'] ?? '/img/placeholder_food.jpg');

    $description = ($description === '') ? null : $description;
    $steps       = ($steps === '') ? null : $steps;

    $timeMin  = (is_numeric($timeMin) && (int)$timeMin >= 0) ? (int)$timeMin : null;
    $servings = (is_numeric($servings) && (int)$servings >= 0) ? (int)$servings : null;

    $sql = "
        UPDATE recipes
        SET title = ?, description = ?, time_min = ?, servings = ?, steps = ?, picture_path = ?
        WHERE id = ?
    ";

    if (!$isAdmin) {
        $sql .= " AND user_id = ?";
    }

    $stmt = $db->prepare($sql);

    if ($isAdmin) {
        $stmt->bind_param(
            "ssiissi",
            $title,
            $description,
            $timeMin,
            $servings,
            $steps,
            $picturePath,
            $recipeId
        );
    } else {
        $stmt->bind_param(
            "ssiissii",
            $title,
            $description,
            $timeMin,
            $servings,
            $steps,
            $picturePath,
            $recipeId,
            $currentUserId
        );
    }

    $ok = $stmt->execute();
    return $ok;
}


function deleteRecipe(int $recipeId, int $currentUserId, bool $isAdmin): bool
{
    $db = getDbConnection();
    $db->begin_transaction();

    try {
        if (!$isAdmin) {
            $stmt = $db->prepare("SELECT user_id FROM recipes WHERE id = ? LIMIT 1");
            $stmt->bind_param("i", $recipeId);
            $stmt->execute();

            $res = $stmt->get_result();
            $row = $res->fetch_assoc();

            if (!$row || (int)$row['user_id'] !== $currentUserId) {
                $db->rollback();
                return false;
            }
        }

        $stmt = $db->prepare("DELETE FROM recipe_tags WHERE recipe_id = ?");
        $stmt->bind_param("i", $recipeId);
        $stmt->execute();

        $stmt = $db->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
        $stmt->bind_param("i", $recipeId);
        $stmt->execute();

        if ($isAdmin) {
            $stmt = $db->prepare("DELETE FROM recipes WHERE id = ?");
            $stmt->bind_param("i", $recipeId);
        } else {
            $stmt = $db->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $recipeId, $currentUserId);
        }

        $stmt->execute();

        $ok = ($stmt->affected_rows > 0);

        if ($ok) {
            $db->commit();
            return true;
        }

        $db->rollback();
        return false;

    } catch (Throwable $e) {
        $db->rollback();
        return false;
    }
}

function addFavorite(int $userId, int $recipeId): bool
{
    $db = getDbConnection();

    $sql = "INSERT IGNORE INTO user_favorites (user_id, recipe_id) VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $userId, $recipeId);

    return $stmt->execute();
}

function removeFavorite(int $userId, int $recipeId): bool
{
    $db = getDbConnection();

    $sql = "DELETE FROM user_favorites WHERE user_id = ? AND recipe_id = ? LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $userId, $recipeId);

    return $stmt->execute();
}

function replaceRecipeTags(int $recipeId, array $tagIds): void
{
    $db = getDbConnection();

    $stmt = $db->prepare("DELETE FROM recipe_tags WHERE recipe_id = ?");
    $stmt->bind_param("i", $recipeId);
    $stmt->execute();

    $tagIds = array_values(array_unique(array_map('intval', $tagIds)));
    if (empty($tagIds)) {
        return;
    }

    $stmtIns = $db->prepare("INSERT INTO recipe_tags (recipe_id, tag_id) VALUES (?, ?)");

    foreach ($tagIds as $tagId) {
        $stmtIns->bind_param("ii", $recipeId, $tagId);
        $stmtIns->execute();
    }
}

function replaceRecipeIngredients(int $recipeId, array $ingredients): void
{
    $db = getDbConnection();

    $stmt = $db->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
    $stmt->bind_param("i", $recipeId);
    $stmt->execute();

    if (empty($ingredients)) {
        return;
    }

    $stmtIns = $db->prepare("
        INSERT INTO recipe_ingredients (recipe_id, quantity, unit, name)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($ingredients as $ing) {
        $qty  = trim((string)($ing['quantity'] ?? ''));
        $unit = trim((string)($ing['unit'] ?? ''));
        $name = trim((string)($ing['name'] ?? ''));

        if ($qty === '' && $unit === '' && $name === '') {
            continue;
        }
        if ($name === '') {
            continue;
        }

        $stmtIns->bind_param("isss", $recipeId, $qty, $unit, $name);
        $stmtIns->execute();
    }
}

function addIngredientsToShoppingList(int $userId, array $ingredients): void
{
    if ($userId <= 0 || empty($ingredients)) {
        return;
    }

    $db = getDbConnection();

    $sql = "
        INSERT INTO shopping_list (user_id, name, quantity, unit)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            quantity = quantity + VALUES(quantity)
    ";

    $stmt = $db->prepare($sql);

    foreach ($ingredients as $ing) {
        $name = trim((string)($ing['name'] ?? ''));
        $unit = trim((string)($ing['unit'] ?? ''));
        $qtyRaw = (string)($ing['quantity'] ?? '');

        $qty = is_numeric($qtyRaw) ? (int)$qtyRaw : 0;

        if ($name === '' || $qty <= 0) {
            continue;
        }

        $stmt->bind_param("isis", $userId, $name, $qty, $unit);
        $stmt->execute();
    }
}

function deleteShoppingListItem(int $itemId, int $userId): bool
{
    $db = getDbConnection();

    $sql = "DELETE FROM shopping_list WHERE id = ? AND user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $itemId, $userId);

    return $stmt->execute();
}

function clearShoppingListByUserId(int $userId): bool
{
    $db = getDbConnection();

    $sql = "DELETE FROM shopping_list WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $userId);

    return $stmt->execute();
}
