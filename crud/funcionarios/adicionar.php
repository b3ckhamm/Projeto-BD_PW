<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') {
    header("Location: /login.php");
    exit;
}

require_once "../../config/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $salario = (float) str_replace(',', '.', $_POST["salario"]);

    $sql = "INSERT INTO funcionarios (nome, email, senha, salario) VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sssd",
        $nome,
        $email,
        $senha,
        $salario
    );

    $stmt->execute();

    header("Location: funcionarios.php");
    exit;
}
?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Adicionar Funcionário - PetLove</title>

<link
    rel="stylesheet"
    href="../../assets/css/crud-funcionarios.css"
>

</head>

<body>

<div class="container">

<h1>
    Pet<span>Love</span>
</h1>

<h2>
    Adicionar funcionário
</h2>


<form method="POST">

    <input
        type="text"
        name="nome"
        placeholder="Nome do funcionário"
        required
    >


    <input
        type="email"
        name="email"
        placeholder="E-mail"
        required
    >


    <input
        type="password"
        name="senha"
        placeholder="Senha"
        required
    >


    <input
        type="number"
        name="salario"
        placeholder="Salário (R$)"
        step="0.01"
        min="0"
        required
    >


    <button type="submit">
        Cadastrar funcionário
    </button>


        <a href="funcionarios.php" class="voltar">
        ← Voltar
    </a>
</form>

</div>

</body>

</html>