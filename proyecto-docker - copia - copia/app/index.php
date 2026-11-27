<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $title = (string) ($_POST['title'] ?? '');
        createTask($pdo, $title);
    } elseif ($action === 'toggle') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id !== false && $id !== null) {
            toggleTask($pdo, $id);
        }
    } elseif ($action === 'delete') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id !== false && $id !== null) {
            deleteTask($pdo, $id);
        }
    }

    header('Location: index.php');
    exit;
}

$tasks = getAllTasks($pdo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas | Docker Compose</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="container">
        <header class="header">
            <h1>Lista de Tareas</h1>
            <p>Aplicación PHP + MySQL ejecutándose sobre Docker Compose</p>
        </header>

        <form method="POST" action="index.php" class="task-form">
            <input type="hidden" name="action" value="create">
            <input
                type="text"
                name="title"
                placeholder="Escribe una nueva tarea..."
                maxlength="255"
                required
            >
            <button type="submit" class="btn btn-primary">Añadir</button>
        </form>

        <section class="task-list-section">
            <?php if (empty($tasks)): ?>
                <p class="empty-state">No hay tareas pendientes. Añade la primera arriba.</p>
            <?php else: ?>
                <ul class="task-list">
                    <?php foreach ($tasks as $task): ?>
                        <?php $isCompleted = (bool) $task['completed']; ?>
                        <li class="task-item <?= $isCompleted ? 'completed' : '' ?>">
                            <div class="task-info">
                                <span class="task-title">
                                    <?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                                <small class="task-date">
                                    <?= htmlspecialchars((string) $task['created_at'], ENT_QUOTES, 'UTF-8') ?>
                                </small>
                            </div>

                            <div class="task-actions">
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="id" value="<?= (int) $task['id'] ?>">
                                    <button type="submit" class="btn btn-toggle">
                                        <?= $isCompleted ? 'Reabrir' : 'Completar' ?>
                                    </button>
                                </form>

                                <form method="POST" action="index.php">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $task['id'] ?>">
                                    <button type="submit" class="btn btn-delete">Eliminar</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>