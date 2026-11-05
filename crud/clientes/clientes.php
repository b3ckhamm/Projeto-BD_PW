<?php

session_start();

if (
    !isset($_SESSION['tipo_usuario']) ||
    !in_array($_SESSION['tipo_usuario'], ['chefe', 'funcionario'])
) {
    header("Location: /login.php");
    exit;
}

require_once "../../config/conexao.php";

$tipo_usuario = $_SESSION['tipo_usuario'];

if ($tipo_usuario === 'chefe') {
    $pagina_inicial = "/chefe/index.php";
} else {
    $pagina_inicial = "/funcionario/index.php";
}

$sql = "SELECT * FROM clientes ORDER BY id DESC";

$clientes = $conexao->query($sql);

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Clientes - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/pets.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <h2>Clientes cadastrados</h2>

    <p class="descricao">
        Consulte e gerencie os clientes cadastrados.
    </p>

    <div class="acoes">

        <a href="adicionar.php" class="adicionar">
            Adicionar cliente
        </a>

        <a href="<?= $pagina_inicial ?>" class="voltar">
            ← Voltar ao início
        </a>

    </div>

    <div class="pets">

        <?php if ($clientes && $clientes->num_rows > 0): ?>

            <?php while ($cliente = $clientes->fetch_assoc()): ?>

                <div class="pet">

                    <h3>
                        <?= htmlspecialchars($cliente['nome']) ?>
                    </h3>

                    <p>
                        <strong>CPF:</strong>
                        <?= htmlspecialchars($cliente['cpf']) ?>
                    </p>

                    <p>
                        <strong>E-mail:</strong>
                        <?= htmlspecialchars($cliente['email']) ?>
                    </p>

                    <p>
                        <strong>Telefone:</strong>
                        <?= htmlspecialchars($cliente['telefone'] ?? 'Não informado') ?>
                    </p>

                    <div class="botoes">

                        <a
                            href="editar.php?id=<?= $cliente['id'] ?>"
                            class="editar"
                        >
                            Editar
                        </a>

                        <a
                            href="excluir.php?id=<?= $cliente['id'] ?>"
                            class="excluir"
                            onclick="return confirm('Tem certeza que deseja excluir este cliente?')"
                        >
                            Excluir
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p class="nenhum">
                Nenhum cliente cadastrado.
            </p>

        <?php endif; ?>

    </div>

</div>

</body>

</html>