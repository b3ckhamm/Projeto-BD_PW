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

$sql = "
    SELECT
        id,
        nome,
        especie,
        raca,
        idade
    FROM animais
    WHERE cliente_id = ?
    ORDER BY nome
";

$stmt = $conexao->prepare($sql);

$stmt->bind_param("i", $cliente_id);

$stmt->execute();

$resultado = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meus Pets - PetLove</title>

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

        <h2>Meus Pets</h2>

        <p class="descricao">
            Consulte e gerencie os pets cadastrados na sua conta.
        </p>

        <div class="acoes">

            <a
                href="/cliente/adicionar_pet.php"
                class="adicionar"
            >
                Adicionar pet
            </a>

            <a
                href="/cliente/index.php"
                class="voltar"
            >
                ← Voltar
            </a>

        </div>

        <div class="pets">

            <?php if ($resultado->num_rows > 0): ?>

                <?php while ($pet = $resultado->fetch_assoc()): ?>

                    <div class="pet">

                        <h3>
                            <?= htmlspecialchars($pet['nome']) ?>
                        </h3>

                        <p>
                            <strong>Espécie:</strong>
                            <?= htmlspecialchars($pet['especie']) ?>
                        </p>

                        <p>
                            <strong>Raça:</strong>
                            <?= htmlspecialchars($pet['raca']) ?>
                        </p>

                        <p>
                            <strong>Idade:</strong>
                            <?= htmlspecialchars($pet['idade']) ?>
                        </p>

                        <div class="botoes">

                            <a
                                href="/cliente/editar_pet.php?id=<?= $pet['id'] ?>"
                                class="editar"
                            >
                                Editar
                            </a>

                            <a
                                href="/cliente/excluir_pet.php?id=<?= $pet['id'] ?>"
                                class="excluir"
                                onclick="return confirm('Tem certeza que deseja excluir este pet?')"
                            >
                                Excluir
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="pet">

                    <h3>Nenhum pet encontrado</h3>

                    <p>
                        Você ainda não possui pets cadastrados.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</body>

</html>