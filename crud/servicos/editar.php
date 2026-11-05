<?php session_start(); if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') { header("Location: /login.php"); exit; } require_once "../../config/conexao.php"; $id = $_GET["id"] ?? null; if (!$id) { header("Location: servicos.php"); exit; } /* |-------------------------------------------------------------------------- | BUSCAR SERVIÇO |-------------------------------------------------------------------------- */ $stmt = $conexao->prepare( "SELECT * FROM servicos WHERE id = ?" ); $stmt->bind_param("i", $id); $stmt->execute(); $resultado = $stmt->get_result(); $servico = $resultado->fetch_assoc(); if (!$servico) { header("Location: servicos.php"); exit; } /* |-------------------------------------------------------------------------- | ATUALIZAR SERVIÇO |-------------------------------------------------------------------------- */ if ($_SERVER["REQUEST_METHOD"] == "POST") { $nome = $_POST["nome"]; $descricao = $_POST["descricao"]; $preco = $_POST["preco"]; $sql = "UPDATE servicos SET nome = ?, descricao = ?, preco = ? WHERE id = ?"; $stmt = $conexao->prepare($sql); $stmt->bind_param( "ssdi", $nome, $descricao, $preco, $id ); $stmt->execute(); header("Location: servicos.php"); exit; } ?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Editar Serviço - PetLove</title>

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
    Editar serviço
</h2>


<form method="POST">

    <input
        type="text"
        name="nome"
        value="<?= htmlspecialchars($servico['nome']) ?>"
        placeholder="Nome do serviço"
        required
    >


    <input
        type="text"
        name="descricao"
        value="<?= htmlspecialchars($servico['descricao'] ?? '') ?>"
        placeholder="Descrição"
    >


    <input
        type="number"
        name="preco"
        value="<?= htmlspecialchars($servico['preco']) ?>"
        placeholder="Preço"
        step="0.01"
        min="0"
        required
    >


    <button type="submit">
        Salvar alterações
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