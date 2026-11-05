<?php session_start(); if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') { header("Location: /login.php"); exit; } require_once "../../config/conexao.php"; if ($_SERVER["REQUEST_METHOD"] == "POST") { $nome = $_POST["nome"]; $descricao = $_POST["descricao"]; $preco = $_POST["preco"]; $sql = "INSERT INTO servicos (nome, descricao, preco) VALUES (?, ?, ?)"; $stmt = $conexao->prepare($sql); $stmt->bind_param( "ssd", $nome, $descricao, $preco ); $stmt->execute(); header("Location: servicos.php"); exit; } ?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Adicionar Serviço - PetLove</title>

<link
    rel="stylesheet"
    <link rel="stylesheet" href="../../assets/css/crud-servicos.css">

</head>

<body>

<div class="container">

<h1>
    Pet<span>Love</span>
</h1>

<h2>
    Adicionar serviço
</h2>


<form method="POST">

    <input
        type="text"
        name="nome"
        placeholder="Nome do serviço"
        required
    >


    <input
        type="text"
        name="descricao"
        placeholder="Descrição"
    >


    <input
        type="number"
        name="preco"
        placeholder="Preço"
        step="0.01"
        min="0"
        required
    >


    <button type="submit">
        Cadastrar serviço
    </button>


    <a
        href="servicos.php"
        class="voltar"
    >
        ← Voltar
    </a>

</form>

</div>

</body>

</html>