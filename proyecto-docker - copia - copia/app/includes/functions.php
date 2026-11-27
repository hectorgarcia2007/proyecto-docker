<?php
declare(strict_types=1);

function getAllTasks(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT id, title, completed, created_at FROM tasks ORDER BY created_at DESC, id DESC');
    return $stmt->fetchAll();
}

function createTask(PDO $pdo, string $title): bool
{
    $cleanTitle = trim($title);
    if ($cleanTitle === '' || mb_strlen($cleanTitle) > 255) {
        return false;
    }

    $stmt = $pdo->prepare('INSERT INTO tasks (title) VALUES (:title)');
    return $stmt->execute([':title' => $cleanTitle]);
}

function toggleTask(PDO $pdo, int $id): bool
{
    if ($id <= 0) {
        return false;
    }

    $stmt = $pdo->prepare('UPDATE tasks SET completed = NOT completed WHERE id = :id');
    return $stmt->execute([':id' => $id]);
}

function deleteTask(PDO $pdo, int $id): bool
{
    if ($id <= 0) {
        return false;
    }

    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
    return $stmt->execute([':id' => $id]);
}