<?php

session_start();

if (
    !isset($_SESSION['tipo_usuario']) ||
    $_SESSION['tipo_usuario'] !== 'cliente'
) {
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . "/../config/conexao.php";

$cliente_id = $_SESSION['usuario_id'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: /cliente/pets.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $especie = trim($_POST['especie']);
    $raca = trim($_POST['raca']);
    $idade = (int) $_POST['idade'];

    $sql = "
        UPDATE animais
        SET
            nome = ?,
            especie = ?,
            raca = ?,
            idade = ?
        WHERE id = ?
        AND cliente_id = ?
    ";

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar atualização: " . $conexao->error);
    }

    $stmt->bind_param(
        "sssiii",
        $nome,
        $especie,
        $raca,
        $idade,
        $id,
        $cliente_id
    );

    if ($stmt->execute()) {
        header('Location: /cliente/pets.php');
        exit;
    }

    die("Erro ao atualizar o pet: " . $stmt->error);
}

$stmt = $conexao->prepare(
    "SELECT * FROM animais WHERE id = ? AND cliente_id = ?"
);

if (!$stmt) {
    die("Erro ao buscar o pet: " . $conexao->error);
}

$stmt->bind_param(
    "ii",
    $id,
    $cliente_id
);

$stmt->execute();

$resultado = $stmt->get_result();

$pet = $resultado->fetch_assoc();

if (!$pet) {
    header('Location: /cliente/pets.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pet - PetLove</title>
    <link rel="stylesheet" href="/assets/css/pets.css">
</head>

<body>

<div class="container">

    <h1>
        Pet<span>Love</span>
    </h1>

    <h2>
        Editar pet
    </h2>

    <p class="descricao">
        Altere os dados do seu pet.
    </p>

    <form method="POST">

        <input
            type="text"
            name="nome"
            value="<?= htmlspecialchars($pet['nome']) ?>"
            placeholder="Nome do pet"
            required
        >

        <select name="especie" required>

            <option
                value="Cachorro"
                <?= $pet['especie'] === 'Cachorro' ? 'selected' : '' ?>
            >
                Cachorro
            </option>

            <option
                value="Gato"
                <?= $pet['especie'] === 'Gato' ? 'selected' : '' ?>
            >
                Gato
            </option>

            <option
                value="Pássaro"
                <?= $pet['especie'] === 'Pássaro' ? 'selected' : '' ?>
            >
                Pássaro
            </option>

            <option
                value="Coelho"
                <?= $pet['especie'] === 'Coelho' ? 'selected' : '' ?>
            >
                Coelho
            </option>

            <option
                value="Outro"
                <?= $pet['especie'] === 'Outro' ? 'selected' : '' ?>
            >
                Outro
            </option>

        </select>

        <input
            type="text"
            name="raca"
            value="<?= htmlspecialchars($pet['raca'] ?? '') ?>"
            placeholder="Raça"
        >

        <input
            type="number"
            name="idade"
            value="<?= htmlspecialchars($pet['idade'] ?? '') ?>"
            min="0"
            placeholder="Idade"
        >

        <button type="submit">
            Salvar alterações
        </button>

    </form>

    <div class="acoes">

        <a
            href="/cliente/pets.php"
            class="voltar"
        >
            ← Voltar
        </a>

    </div>

</div>

</body>
</html>