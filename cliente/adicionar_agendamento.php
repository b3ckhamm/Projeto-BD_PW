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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $animal_id = $_POST["animal_id"];
    $servico_id = $_POST["servico_id"];

    $tipo_banho = !empty($_POST["tipo_banho"])
        ? $_POST["tipo_banho"]
        : null;

    $tipo_tosa = !empty($_POST["tipo_tosa"])
        ? $_POST["tipo_tosa"]
        : null;

    $data = $_POST["data"];
    $horario = $_POST["horario"];

    $observacao = !empty($_POST["observacao"])
        ? $_POST["observacao"]
        : null;

    $verifica = $conexao->prepare(
        "SELECT id FROM animais WHERE id = ? AND cliente_id = ?"
    );

    $verifica->bind_param(
        "ii",
        $animal_id,
        $cliente_id
    );

    $verifica->execute();

    $resultado_verificacao = $verifica->get_result();

    if ($resultado_verificacao->num_rows === 0) {
        die("O pet selecionado não pertence à sua conta.");
    }

    $sql = "
        INSERT INTO agendamentos (
            animal_id,
            servico_id,
            tipo_banho,
            tipo_tosa,
            data,
            horario,
            observacao
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "iisssss",
        $animal_id,
        $servico_id,
        $tipo_banho,
        $tipo_tosa,
        $data,
        $horario,
        $observacao
    );

    $stmt->execute();

    header("Location: /cliente/agendamentos.php");
    exit;
}

$sql_pets = "
    SELECT id, nome
    FROM animais
    WHERE cliente_id = ?
    ORDER BY nome
";

$stmt_pets = $conexao->prepare($sql_pets);

$stmt_pets->bind_param(
    "i",
    $cliente_id
);

$stmt_pets->execute();

$resultado_pets = $stmt_pets->get_result();

$sql_servicos = "
    SELECT id, nome
    FROM servicos
    ORDER BY nome
";

$servicos = $conexao->query($sql_servicos);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Agendar Serviço - PetLove</title>

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

        <h2>
            Agendar serviço
        </h2>

        <p class="descricao">
            Escolha seu pet e o serviço que deseja agendar.
        </p>

        <?php if ($resultado_pets->num_rows === 0): ?>

            <div class="pet">

                <h3>
                    Nenhum pet cadastrado
                </h3>

                <p>
                    Você precisa cadastrar um pet antes de realizar um agendamento.
                </p>

                <div class="acoes">

                    <a
                        href="/cliente/adicionar_pet.php"
                        class="adicionar"
                    >
                        Cadastrar pet
                    </a>

                </div>

            </div>

        <?php else: ?>

            <form method="POST">

                <select
                    name="animal_id"
                    required
                >

                    <option value="">
                        Selecione seu pet
                    </option>

                    <?php while ($pet = $resultado_pets->fetch_assoc()): ?>

                        <option value="<?= $pet['id'] ?>">
                            <?= htmlspecialchars($pet['nome']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>

                <select
                    name="servico_id"
                    required
                >

                    <option value="">
                        Selecione o serviço
                    </option>

                    <?php if ($servicos && $servicos->num_rows > 0): ?>

                        <?php while ($servico = $servicos->fetch_assoc()): ?>

                            <option value="<?= $servico['id'] ?>">
                                <?= htmlspecialchars($servico['nome']) ?>
                            </option>

                        <?php endwhile; ?>

                    <?php endif; ?>

                </select>

                <select name="tipo_banho">

                    <option value="">
                        Tipo de banho (opcional)
                    </option>

                    <option value="Banho Tradicional">
                        Banho Tradicional
                    </option>

                    <option value="Banho Hidratante">
                        Banho Hidratante
                    </option>

                    <option value="Banho Antipulgas">
                        Banho Antipulgas
                    </option>

                    <option value="Banho para Filhotes">
                        Banho para Filhotes
                    </option>

                </select>

                <select name="tipo_tosa">

                    <option value="">
                        Tipo de tosa (opcional)
                    </option>

                    <option value="Tosa Higiênica">
                        Tosa Higiênica
                    </option>

                    <option value="Tosa na Máquina">
                        Tosa na Máquina
                    </option>

                    <option value="Tosa na Tesoura">
                        Tosa na Tesoura
                    </option>

                    <option value="Tosa Bebê">
                        Tosa Bebê
                    </option>

                </select>

                <input
                    type="date"
                    name="data"
                    required
                >

                <input
                    type="time"
                    name="horario"
                    required
                >

                <input
                    type="text"
                    name="observacao"
                    placeholder="Observação (opcional)"
                >

                <button type="submit">
                    Agendar
                </button>

            </form>

        <?php endif; ?>

        <div class="acoes">

            <a
                href="/cliente/agendamentos.php"
                class="voltar"
            >
                ← Voltar
            </a>

        </div>

    </div>

</body>

</html>