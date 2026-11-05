<?php

session_start();

require_once __DIR__ . "/config/conexao.php";

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    if ($email === '') {

        $mensagem = 'Digite um e-mail.';
        $tipo_mensagem = 'erro';

    } else {

        $stmt = $conexao->prepare(
            "SELECT id FROM clientes WHERE email = ?"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $mensagem = 'Código enviado! Verifique sua caixa de entrada.';
            $tipo_mensagem = 'sucesso';

        } else {

            $mensagem = 'Este e-mail não está cadastrado.';
            $tipo_mensagem = 'erro';
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

    <title>Recuperar Senha - PetLove</title>

    <link
        rel="stylesheet"
        href="/assets/css/recuperar_senha.css"
    >

    <style>
        body {
            background-image: url("img/esqueci_senha.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>

</head>

<body>

    <header>

        <h1>PetLove</h1>

    </header>

    <main>

        <div class="caixa-recuperacao">

            <h2>Recuperar Senha</h2>

            <p>
                Digite seu e-mail cadastrado e enviaremos um link para você
                redefinir sua senha.
            </p>

            <?php if ($mensagem): ?>

                <div class="mensagem <?= htmlspecialchars($tipo_mensagem) ?>">

                    <?= htmlspecialchars($mensagem) ?>

                </div>

            <?php endif; ?>

            <form method="POST" action="">

                <div class="campo">

                    <label for="email">
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="seuemail@exemplo.com"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn-enviar"
                >
                    Enviar link de recuperação
                </button>

            </form>

            <a
                href="/login.php"
                class="voltar"
            >
                Voltar para o login
            </a>

        </div>

    </main>

    <footer>

        <p>
            &copy; 2026 PetLove. Todos os direitos reservados.
        </p>

    </footer>

</body>

</html>