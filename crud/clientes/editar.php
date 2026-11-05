<?php

require_once "../../config/conexao.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: clientes.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];

    $sql = "UPDATE clientes
            SET nome = ?,
                cpf = ?,
                email = ?,
                telefone = ?
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ssssi",
        $nome,
        $cpf,
        $email,
        $telefone,
        $id
    );

    $stmt->execute();

    header("Location: clientes.php");
    exit;
}

$stmt = $conexao->prepare(
    "SELECT * FROM clientes WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();

$cliente = $resultado->fetch_assoc();

if (!$cliente) {
    header("Location: clientes.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Cliente - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/pets.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <form method="POST">

        <h2>Editar cliente</h2>

        <input
            type="text"
            name="nome"
            value="<?= htmlspecialchars($cliente['nome']) ?>"
            required
        >

        <input
            type="text"
            name="cpf"
            value="<?= htmlspecialchars($cliente['cpf']) ?>"
            required
        >

        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($cliente['email']) ?>"
            required
        >

        <input
            type="text"
            name="telefone"
            value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>"
            placeholder="Telefone"
        >

        <button type="submit">
            Salvar alterações
        </button>

        <a href="clientes.php" class="voltar">
            ← Voltar
        </a>

    </form>

</div>

</body>

</html>
