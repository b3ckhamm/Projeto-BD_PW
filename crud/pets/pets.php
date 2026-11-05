<?php

session_start();

if (
    !isset($_SESSION['tipo_usuario']) ||
    (
        $_SESSION['tipo_usuario'] !== 'chefe' &&
        $_SESSION['tipo_usuario'] !== 'funcionario'
    )
) {
    header("Location: /login.php");
    exit;
}

require_once "../../config/conexao.php";

$tipo_usuario = $_SESSION['tipo_usuario'];

if ($tipo_usuario === 'chefe') {
    $inicio = '/chefe/index.php';
} else {
    $inicio = '/funcionario/index.php';
}


$sql = "SELECT
            animais.id,
            animais.nome,
            animais.especie,
            animais.raca,
            animais.idade,
            clientes.nome AS dono

        FROM animais

        INNER JOIN clientes
            ON animais.cliente_id = clientes.id

        ORDER BY animais.id DESC";

$pets = $conexao->query($sql);

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pets - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/pets.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <h2>Pets cadastrados</h2>

    <p class="descricao">
        Consulte e gerencie os pets cadastrados.
    </p>


    <div class="acoes">

        <a href="adicionar.php" class="adicionar">
            Adicionar pet
        </a>

        <a href="<?= $inicio ?>" class="voltar">
            ← Voltar ao início
        </a>

    </div>


    <div class="pets">

        <?php if ($pets && $pets->num_rows > 0): ?>

            <?php while ($pet = $pets->fetch_assoc()): ?>

                <div class="pet">

                    <h3>
                        <?= htmlspecialchars($pet['nome']) ?>
                    </h3>


                    <p>
                        <strong>Dono:</strong>
                        <?= htmlspecialchars($pet['dono']) ?>
                    </p>


                    <p>
                        <strong>Animal:</strong>
                        <?= htmlspecialchars($pet['especie']) ?>
                    </p>


                    <p>
                        <strong>Raça:</strong>
                        <?= htmlspecialchars($pet['raca'] ?? 'Não informada') ?>
                    </p>


                    <p>
                        <strong>Idade:</strong>
                        <?= htmlspecialchars($pet['idade'] ?? 'Não informada') ?>

                        <?php if ($pet['idade'] !== null): ?>
                            anos
                        <?php endif; ?>

                    </p>


                    <div class="botoes">

                        <a
                            href="editar.php?id=<?= $pet['id'] ?>"
                            class="editar"
                        >
                            Editar
                        </a>


                        <a
                            href="excluir.php?id=<?= $pet['id'] ?>"
                            class="excluir"
                            onclick="return confirm('Tem certeza que deseja excluir este pet?')"
                        >
                            Excluir
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p class="nenhum">
                Nenhum pet cadastrado.
            </p>

        <?php endif; ?>

    </div>

</div>

</body>

</html>
