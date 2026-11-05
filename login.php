<?php

session_start();

require_once __DIR__ . "/config/conexao.php";

$erro = '';

$redirect = $_GET['redirect'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {

        $erro = 'Preencha todos os campos.';

    } else {

        $stmt = $conexao->prepare(
            "SELECT id, nome, senha FROM clientes WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $usuario = $resultado->fetch_assoc();

            if ($senha === $usuario['senha']) {

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['tipo_usuario'] = 'cliente';

                if ($redirect !== '') {
                    header("Location: " . $redirect);
                } else {
                    header("Location: cliente/index.php");
                }

                exit;

            } else {

                $erro = 'E-mail ou senha incorretos.';
            }

        } else {

            $stmt = $conexao->prepare(
                "SELECT id, nome, senha FROM funcionarios WHERE email = ?"
            );

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {

                $usuario = $resultado->fetch_assoc();

                if ($senha === $usuario['senha']) {

                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['tipo_usuario'] = 'funcionario';

                    header("Location: funcionario/index.php");
                    exit;

                } else {

                    $erro = 'E-mail ou senha incorretos.';
                }

            } else {

                $stmt = $conexao->prepare(
                    "SELECT id, nome, senha FROM chefes WHERE email = ?"
                );

                $stmt->bind_param("s", $email);
                $stmt->execute();

                $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0) {

                    $usuario = $resultado->fetch_assoc();

                    if ($senha === $usuario['senha']) {

                        $_SESSION['usuario_id'] = $usuario['id'];
                        $_SESSION['usuario_nome'] = $usuario['nome'];
                        $_SESSION['tipo_usuario'] = 'chefe';

                        header("Location: chefe/");
                        exit;

                    } else {

                        $erro = 'E-mail ou senha incorretos.';
                    }

                } else {

                    $erro = 'E-mail ou senha incorretos.';
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

    <title>Login - PetLove</title>

    <link
        rel="stylesheet"
        href="assets/css/login.css"
    >

    <style>

        body {
            background-image: url("img/login.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

    </style>

</head>

<body>

<div class="container">

    <form method="POST">

        <h1>PetLove</h1>

        <h2>Login</h2>

        <?php if ($erro): ?>

            <p class="erro">
                <?php echo htmlspecialchars($erro); ?>
            </p>

        <?php endif; ?>

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
                type="password"
                name="senha"
                placeholder="Senha"
                required
            >

        </div>

        <div class="remember">

            <label>

                <input
                    type="checkbox"
                    name="lembrar"
                >

                Lembrar de mim

            </label>

            <a href="recuperar_senha.php">
                Esqueci a senha
            </a>

        </div>

        <button
            type="submit"
            class="login"
        >
            Entrar
        </button>

        <a
            href="index.html"
            class="voltar"
        >
            Voltar
        </a>

        <div class="registro">

            <p>
                Ainda não possui uma conta?
            </p>

            <a href="cadastro.php">
                Registre-se
            </a>

        </div>

    </form>

</div>

</body>

</html>