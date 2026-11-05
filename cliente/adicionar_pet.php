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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $especie = $_POST["especie"];
    $raca = $_POST["raca"];
    $idade = $_POST["idade"];

    $sql = "
        INSERT INTO animais (
            nome,
            especie,
            raca,
            idade,
            cliente_id
        )
        VALUES (?, ?, ?, ?, ?)
    ";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sssii",
        $nome,
        $especie,
        $raca,
        $idade,
        $cliente_id
    );

    $stmt->execute();

    header("Location: /cliente/pets.php");
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

    <title>Adicionar Pet - PetLove</title>

    <link
        rel="stylesheet"
        href="/assets/css/pets.css"
    >

</head>

<body>

    <div class="container">

        <h1>
            Pet<span>Love</span>
        </h1>

        <h2>Adicionar pet</h2>

        <form method="POST">

            <input
                type="text"
                name="nome"
                placeholder="Nome do pet"
                required
            >

            <select
                name="especie"
                required
            >

                <option value="">
                    Selecione a espécie
                </option>

                <option value="Cachorro">
                    Cachorro
                </option>

                <option value="Gato">
                    Gato
                </option>

                <option value="Pássaro">
                    Pássaro
                </option>

                <option value="Coelho">
                    Coelho
                </option>

                <option value="Outro">
                    Outro
                </option>

            </select>

            <input
                type="text"
                name="raca"
                placeholder="Raça"
            >

            <input
                type="number"
                name="idade"
                placeholder="Idade"
                min="0"
            >

            <button type="submit">
                Cadastrar pet
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