<?php

require_once "../../config/conexao.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: pets.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $especie = $_POST["especie"];
    $raca = $_POST["raca"];
    $idade = $_POST["idade"];
    $cliente_id = $_POST["cliente_id"];

    $sql = "UPDATE animais
            SET nome = ?,
                especie = ?,
                raca = ?,
                idade = ?,
                cliente_id = ?
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sssiii",
        $nome,
        $especie,
        $raca,
        $idade,
        $cliente_id,
        $id
    );

    $stmt->execute();

    header("Location: pets.php");
    exit;
}

$stmt = $conexao->prepare(
    "SELECT * FROM animais WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$pet = $resultado->fetch_assoc();

if (!$pet) {
    header("Location: pets.php");
    exit;
}

$clientes = $conexao->query(
    "SELECT id, nome FROM clientes ORDER BY nome"
);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Pet - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/pets.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <h2>Editar pet</h2>

    <form method="POST">

        <input
            type="text"
            name="nome"
            value="<?= htmlspecialchars($pet['nome']) ?>"
            required
        >

        <select name="especie" required>

            <option value="Cachorro" <?= $pet['especie'] == 'Cachorro' ? 'selected' : '' ?>>
                Cachorro
            </option>

            <option value="Gato" <?= $pet['especie'] == 'Gato' ? 'selected' : '' ?>>
                Gato
            </option>

            <option value="Pássaro" <?= $pet['especie'] == 'Pássaro' ? 'selected' : '' ?>>
                Pássaro
            </option>

            <option value="Coelho" <?= $pet['especie'] == 'Coelho' ? 'selected' : '' ?>>
                Coelho
            </option>

            <option value="Outro" <?= $pet['especie'] == 'Outro' ? 'selected' : '' ?>>
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

        <select name="cliente_id" required>

            <?php while ($cliente = $clientes->fetch_assoc()): ?>

                <option
                    value="<?= $cliente['id'] ?>"
                    <?= $cliente['id'] == $pet['cliente_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($cliente['nome']) ?>
                </option>

            <?php endwhile; ?>

        </select>

    <button type="submit">
    Salvar alterações
</button>

<a href="pets.php" class="voltar">
    ← Voltar
</a>

</form>

</div>

</body>

</html>