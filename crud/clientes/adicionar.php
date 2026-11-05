<?php

require_once "../../config/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $senha = $_POST["senha"];

    $sql = "INSERT INTO clientes
            (nome, cpf, email, telefone, senha)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sssss",
        $nome,
        $cpf,
        $email,
        $telefone,
        $senha
    );

    $stmt->execute();

    header("Location: clientes.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Adicionar Cliente - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/pets.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <form method="POST">

        <h2>Adicionar cliente</h2>

        <input
            type="text"
            name="nome"
            placeholder="Nome completo"
            required
        >

        <input
            type="text"
            name="cpf"
            placeholder="CPF"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="E-mail"
            required
        >

        <input
            type="text"
            name="telefone"
            placeholder="Telefone"
        >

        <input
            type="password"
            name="senha"
            placeholder="Senha"
            required
        >

        <button type="submit">
            Cadastrar cliente
        </button>

        <a href="clientes.php" class="voltar">
            ← Voltar
        </a>

    </form>

</div>

</body>

</html>
