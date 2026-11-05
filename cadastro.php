<?php

require_once __DIR__ . '/config/conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = $_POST['senha'];

    if ($nome === '' || $cpf === '' || $email === '' || $senha === '') {

        $erro = 'Preencha todos os campos obrigatórios.';

    } else {

        $stmt = $conexao->prepare(
            "SELECT id FROM clientes WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $erro = 'Este e-mail já está cadastrado.';

        } else {

            $stmt = $conexao->prepare(
                "SELECT id FROM clientes WHERE cpf = ?"
            );

            $stmt->bind_param("s", $cpf);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {

                $erro = 'Este CPF já está cadastrado.';

            } else {

                $stmt = $conexao->prepare(
                    "INSERT INTO clientes (nome, cpf, email, telefone, senha)
                     VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "sssss",
                    $nome,
                    $cpf,
                    $email,
                    $telefone,
                    $senha
                );

                if ($stmt->execute()) {

                    $sucesso = 'Cadastro realizado com sucesso!';

                } else {

                    $erro = 'Não foi possível realizar o cadastro.';
                }
            }
        }
    }
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

    <title>Cadastro - PetLove</title>

    <link
        rel="stylesheet"
        href="/assets/css/cadastro.css"
    >

    <style>
        body {
            background-image: url("img/cadastro.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>

</head>

<body>

<main class="container">

    <form method="POST">

        <h1>Cadastro PetLove</h1>

        <?php if ($erro): ?>

            <p class="erro">
                <?= htmlspecialchars($erro) ?>
            </p>

        <?php endif; ?>

        <?php if ($sucesso): ?>

            <p class="sucesso">
                <?= htmlspecialchars($sucesso) ?>
            </p>

        <?php endif; ?>

        <div class="input-box">

            <input
                type="text"
                name="nome"
                placeholder="Nome completo"
                required
            >

        </div>

        <div class="input-box">

            <input
                type="text"
                name="cpf"
                placeholder="CPF"
                required
            >

        </div>

        <div class="input-box">

            <input
                type="email"
                name="email"
                placeholder="E-mail"
                required
            >

        </div>

        <div class="input-box">

            <input
                type="text"
                name="telefone"
                placeholder="Telefone"
            >

        </div>

        <div class="input-box">

            <input
                type="password"
                name="senha"
                placeholder="Senha"
                required
            >

        </div>

        <button
            type="submit"
            class="login"
        >
            Cadastrar
        </button>

        <a
            href="/login.php"
            class="voltar"
        >
            Voltar para o login
        </a>

    </form>

</main>

</body>
</html>