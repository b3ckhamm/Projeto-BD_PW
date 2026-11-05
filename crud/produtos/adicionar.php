<?php

session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') {
    header("Location: /login.php");
    exit;
}

require_once "../../config/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $preco = $_POST["preco"];
    $estoque = $_POST["estoque"];
    $categoria = $_POST["categoria"];

    /*
     * IMAGEM
     */

    $imagem = null;

    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] == 0) {

        $arquivo = $_FILES["imagem"];

        $extensao = strtolower(
            pathinfo($arquivo["name"], PATHINFO_EXTENSION)
        );

        $extensoes_permitidas = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];

        if (!in_array($extensao, $extensoes_permitidas)) {
            die("Formato de imagem não permitido.");
        }

        /*
         * Cria um nome único para evitar
         * conflito entre arquivos.
         */

        $nome_arquivo = uniqid("produto_") . "." . $extensao;

        $pasta = $_SERVER["DOCUMENT_ROOT"] . "/assets/img/";

        $caminho = $pasta . $nome_arquivo;

        if (!move_uploaded_file($arquivo["tmp_name"], $caminho)) {
            die("Erro ao salvar a imagem.");
        }

        $imagem = $nome_arquivo;
    }


    /*
     * CADASTRA O PRODUTO
     */

    $sql = "INSERT INTO produtos
            (nome, descricao, preco, estoque, categoria, imagem)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ssdiss",
        $nome,
        $descricao,
        $preco,
        $estoque,
        $categoria,
        $imagem
    );

    $stmt->execute();

    header("Location: produtos.php");
    exit;
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Adicionar Produto - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/crud-produtos.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <h2>Adicionar produto</h2>


    <form method="POST" enctype="multipart/form-data">

        <input
            type="text"
            name="nome"
            placeholder="Nome do produto"
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


        <input
            type="number"
            name="estoque"
            placeholder="Quantidade em estoque"
            min="0"
            required
        >


        <select name="categoria" required>

            <option value="">
                Selecione a categoria
            </option>

            <option value="Ração">
                Ração
            </option>

            <option value="Brinquedos">
                Brinquedos
            </option>

            <option value="Higiene">
                Higiene
            </option>

            <option value="Acessórios">
                Acessórios
            </option>

            <option value="Petiscos">
                Petiscos
            </option>

        </select>


        <label class="label-imagem">
            Imagem do produto
        </label>

        <input
            type="file"
            name="imagem"
            accept=".jpg,.jpeg,.png,.webp"
        >


        <button type="submit">
            Cadastrar produto
        </button>


        <a href="produtos.php" class="voltar">
            ← Voltar
        </a>

    </form>

</div>

</body>

</html>