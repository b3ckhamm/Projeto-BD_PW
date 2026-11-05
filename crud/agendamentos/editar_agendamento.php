<?php

session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') {
    header("Location: /login.php");
    exit;
}

require_once "../../config/conexao.php";


$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: agendamentos.php");
    exit;
}


/* ATUALIZAR */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $animal_id = $_POST["animal_id"];
    $servico_id = $_POST["servico_id"];
    $tipo_banho = !empty($_POST["tipo_banho"]) ? $_POST["tipo_banho"] : null;
    $tipo_tosa = !empty($_POST["tipo_tosa"]) ? $_POST["tipo_tosa"] : null;
    $data = $_POST["data"];
    $horario = $_POST["horario"];
    $status = $_POST["status"];
    $observacao = $_POST["observacao"];


    $sql = "UPDATE agendamentos

            SET animal_id = ?,
                servico_id = ?,
                tipo_banho = ?,
                tipo_tosa = ?,
                data = ?,
                horario = ?,
                status = ?,
                observacao = ?

            WHERE id = ?";


    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "iissssssi",
        $animal_id,
        $servico_id,
        $tipo_banho,
        $tipo_tosa,
        $data,
        $horario,
        $status,
        $observacao,
        $id
    );

    $stmt->execute();

    header("Location: agendamentos.php");
    exit;
}


/* BUSCA AGENDAMENTO */

$stmt = $conexao->prepare(
    "SELECT *
     FROM agendamentos
     WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();

$agendamento = $resultado->fetch_assoc();


if (!$agendamento) {

    header("Location: agendamentos.php");
    exit;

}


/* BUSCA PETS */

$pets = $conexao->query(
    "SELECT
        animais.id,
        animais.nome AS pet,
        clientes.nome AS dono
     FROM animais

     INNER JOIN clientes
        ON animais.cliente_id = clientes.id

     ORDER BY animais.nome"
);


/* BUSCA SERVIÇOS */

$servicos = $conexao->query(
    "SELECT id, nome
     FROM servicos
     ORDER BY nome"
);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Agendamento - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/crud-agendamentos.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <h2>Editar agendamento</h2>


    <form method="POST">


        <select name="animal_id" required>

            <?php while ($pet = $pets->fetch_assoc()): ?>

                <option
                    value="<?= $pet['id'] ?>"
                    <?= $pet['id'] == $agendamento['animal_id'] ? 'selected' : '' ?>
                >

                    <?= htmlspecialchars($pet['pet']) ?>
                    -
                    <?= htmlspecialchars($pet['dono']) ?>

                </option>

            <?php endwhile; ?>

        </select>


        <select name="servico_id" required>

            <?php while ($servico = $servicos->fetch_assoc()): ?>

                <option
                    value="<?= $servico['id'] ?>"
                    <?= $servico['id'] == $agendamento['servico_id'] ? 'selected' : '' ?>
                >

                    <?= htmlspecialchars($servico['nome']) ?>

                </option>

            <?php endwhile; ?>

        </select>


        <select name="tipo_banho">

            <option value="">
                Sem tipo de banho
            </option>

            <option value="Banho Tradicional"
                <?= $agendamento['tipo_banho'] == 'Banho Tradicional' ? 'selected' : '' ?>>
                Banho Tradicional
            </option>

            <option value="Banho Hidratante"
                <?= $agendamento['tipo_banho'] == 'Banho Hidratante' ? 'selected' : '' ?>>
                Banho Hidratante
            </option>

            <option value="Banho Antipulgas"
                <?= $agendamento['tipo_banho'] == 'Banho Antipulgas' ? 'selected' : '' ?>>
                Banho Antipulgas
            </option>

            <option value="Banho para Filhotes"
                <?= $agendamento['tipo_banho'] == 'Banho para Filhotes' ? 'selected' : '' ?>>
                Banho para Filhotes
            </option>

        </select>


        <select name="tipo_tosa">

            <option value="">
                Sem tipo de tosa
            </option>

            <option value="Tosa Higiênica"
                <?= $agendamento['tipo_tosa'] == 'Tosa Higiênica' ? 'selected' : '' ?>>
                Tosa Higiênica
            </option>

            <option value="Tosa na Máquina"
                <?= $agendamento['tipo_tosa'] == 'Tosa na Máquina' ? 'selected' : '' ?>>
                Tosa na Máquina
            </option>

            <option value="Tosa na Tesoura"
                <?= $agendamento['tipo_tosa'] == 'Tosa na Tesoura' ? 'selected' : '' ?>>
                Tosa na Tesoura
            </option>

            <option value="Tosa Bebê"
                <?= $agendamento['tipo_tosa'] == 'Tosa Bebê' ? 'selected' : '' ?>>
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
            value="<?= htmlspecialchars(substr($agendamento['horario'], 0, 5)) ?>"
            required
        >


        <select name="status" required>

            <option value="Agendado"
                <?= $agendamento['status'] == 'Agendado' ? 'selected' : '' ?>>
                Agendado
            </option>

            <option value="Confirmado"
                <?= $agendamento['status'] == 'Confirmado' ? 'selected' : '' ?>>
                Confirmado
            </option>

            <option value="Concluído"
                <?= $agendamento['status'] == 'Concluído' ? 'selected' : '' ?>>
                Concluído
            </option>

            <option value="Cancelado"
                <?= $agendamento['status'] == 'Cancelado' ? 'selected' : '' ?>>
                Cancelado
            </option>

        </select>


        <input
            type="text"
            name="observacao"
            value="<?= htmlspecialchars($agendamento['observacao'] ?? '') ?>"
            placeholder="Observação (opcional)"
        >


        <button type="submit">
            Salvar alterações
        </button>


        <a href="agendamentos.php" class="voltar">
            ← Voltar
        </a>

    </form>

</div>

</body>

</html>