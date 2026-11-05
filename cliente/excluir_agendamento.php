```php
<?php

session_start();

if (
    !isset($_SESSION['tipo_usuario']) ||
    $_SESSION['tipo_usuario'] !== 'cliente'
) {
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . "/config/conexao.php";

$cliente_id = $_SESSION['usuario_id'];

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /cliente/agendamentos.php');
    exit;
}

$sql = "
    DELETE agendamentos
    FROM agendamentos
    INNER JOIN animais
        ON agendamentos.animal_id = animais.id
    WHERE agendamentos.id = ?
    AND animais.cliente_id = ?
";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "ii",
    $id,
    $cliente_id
);

$stmt->execute();

header('Location: /cliente/agendamentos.php');
exit;

?>
```
