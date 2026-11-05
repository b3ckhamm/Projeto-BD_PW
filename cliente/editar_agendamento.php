```php
<?php

session_start();

if (
    !isset($_SESSION['tipo_usuario']) ||
    $_SESSION['tipo_usuario'] !== 'cliente'
) {
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . "/config/conexao.php";

$cliente_id = $_SESSION['usuario_id'];

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /cliente/agendamentos.php');
    exit;
}

$sql = "
    SELECT
        agendamentos.*
    FROM agendamentos
    INNER JOIN animais
        ON agendamentos.animal_id = animais.id
    WHERE agendamentos.id = ?
    AND animais.cliente_id = ?
";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "ii",
    $id,
    $cliente_id
);

$stmt->execute();

$resultado = $stmt->get_result();

$agendamento = $resultado->fetch_assoc();

if (!$agendamento) {
    header('Location: /cliente/agendamentos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $animal_id = $_POST['animal_id'];
    $servico_id = $_POST['servico_id'];

    $tipo_banho = !empty($_POST['tipo_banho'])
        ? $_POST['tipo_banho']
        : null;

    $tipo_tosa = !empty($_POST['tipo_tosa'])
        ? $_POST['tipo_tosa']
        : null;

    $data = $_POST['data'];
    $horario = $_POST['horario'];

    $observacao = !empty($_POST['observacao'])
        ? $_POST['observacao']
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
        die('O pet selecionado não pertence à sua conta.');
    }

    $sql = "
        UPDATE agendamentos
        SET
            animal_id = ?,
            servico_id = ?,
            tipo_banho = ?,
            tipo_tosa = ?,
            data = ?,
            horario = ?,
            observacao = ?
        WHERE id = ?
    ";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "iisssssi",
        $animal_id,
        $servico_id,
        $tipo_banho,
        $tipo_tosa,
        $data,
        $horario,
        $observacao,
        $id
    );

    $stmt->execute();

    header('Location: /cliente/agendamentos.php');
    exit;
}

$sql_pets = "
    SELECT
        id,
        nome
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
    SELECT
        id,
        nome
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

    <title>Editar Agendamento - PetLove</title>

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
            Editar agendamento
        </h2>

        <p class="descricao">
            Altere os dados do seu agendamento.
        </p>

        <form method="POST">

            <select
                name="animal_id"
                required
            >

                <option value="">
                    Selecione seu pet
                </option>

                <?php while ($pet = $resultado_pets->fetch_assoc()): ?>

                    <option
                        value="<?= $pet['id'] ?>"
                        <?= $pet['id'] == $agendamento['animal_id']
                            ? 'selected'
                            : '' ?>
                    >
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

                        <option
                            value="<?= $servico['id'] ?>"
                            <?= $servico['id'] == $agendamento['servico_id']
                                ? 'selected'
                                : '' ?>
                        >
                            <?= htmlspecialchars($servico['nome']) ?>
                        </option>

                    <?php endwhile; ?>

                <?php endif; ?>

            </select>

            <select name="tipo_banho">

                <option value="">
                    Sem tipo de banho
                </option>

                <option
                    value="Banho Tradicional"
                    <?= $agendamento['tipo_banho'] == 'Banho Tradicional'
                        ? 'selected'
                        : '' ?>
                >
                    Banho Tradicional
                </option>

                <option
                    value="Banho Hidratante"
                    <?= $agendamento['tipo_banho'] == 'Banho Hidratante'
                        ? 'selected'
                        : '' ?>
                >
                    Banho Hidratante
                </option>

                <option
                    value="Banho Antipulgas"
                    <?= $agendamento['tipo_banho'] == 'Banho Antipulgas'
                        ? 'selected'
                        : '' ?>
                >
                    Banho Antipulgas
                </option>

                <option
                    value="Banho para Filhotes"
                    <?= $agendamento['tipo_banho'] == 'Banho para Filhotes'
                        ? 'selected'
                        : '' ?>
                >
                    Banho para Filhotes
                </option>

            </select>

            <select name="tipo_tosa">

                <option value="">
                    Sem tipo de tosa
                </option>

                <option
                    value="Tosa Higiênica"
                    <?= $agendamento['tipo_tosa'] == 'Tosa Higiênica'
                        ? 'selected'
                        : '' ?>
                >
                    Tosa Higiênica
                </option>

                <option
                    value="Tosa na Máquina"
                    <?= $agendamento['tipo_tosa'] == 'Tosa na Máquina'
                        ? 'selected'
                        : '' ?>
                >
                    Tosa na Máquina
                </option>

                <option
                    value="Tosa na Tesoura"
                    <?= $agendamento['tipo_tosa'] == 'Tosa na Tesoura'
                        ? 'selected'
                        : '' ?>
                >
                    Tosa na Tesoura
                </option>

                <option
                    value="Tosa Bebê"
                    <?= $agendamento['tipo_tosa'] == 'Tosa Bebê'
                        ? 'selected'
                        : '' ?>
                >
                    Tosa Bebê
                </option>

            </select>

            <input
                type="date"
                name="data"
                value="<?= htmlspecialchars($agendamento['data']) ?>"
                required
            >

            <input
                type="time"
                name="horario"
                value="<?= htmlspecialchars(
                    substr($agendamento['horario'], 0, 5)
                ) ?>"
                required
            >

            <input
                type="text"
                name="observacao"
                value="<?= htmlspecialchars(
                    $agendamento['observacao'] ?? ''
                ) ?>"
                placeholder="Observação (opcional)"
            >

            <button type="submit">
                Salvar alterações
            </button>

        </form>

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
```
