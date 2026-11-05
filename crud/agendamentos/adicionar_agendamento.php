<?php

session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') {
    header("Location: /login.php");
    exit;
}

require_once "../../config/conexao.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $animal_id = $_POST["animal_id"];
    $servico_id = $_POST["servico_id"];
    $tipo_banho = !empty($_POST["tipo_banho"]) ? $_POST["tipo_banho"] : null;
    $tipo_tosa = !empty($_POST["tipo_tosa"]) ? $_POST["tipo_tosa"] : null;
    $data = $_POST["data"];
    $horario = $_POST["horario"];
    $status = $_POST["status"];
    $observacao = $_POST["observacao"];


    $sql = "INSERT INTO agendamentos
            (animal_id, servico_id, tipo_banho, tipo_tosa, data, horario, status, observacao)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "iissssss",
        $animal_id,
        $servico_id,
        $tipo_banho,
        $tipo_tosa,
        $data,
        $horario,
        $status,
        $observacao
    );

    $stmt->execute();

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

    <title>Adicionar Agendamento - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/crud-agendamentos.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <h2>Adicionar agendamento</h2>

    <form method="POST">


        <select name="animal_id" required>

            <option value="">
                Selecione o pet
            </option>

            <?php while ($pet = $pets->fetch_assoc()): ?>

                <option value="<?= $pet['id'] ?>">

                    <?= htmlspecialchars($pet['pet']) ?>
                    -
                    <?= htmlspecialchars($pet['dono']) ?>

                </option>

            <?php endwhile; ?>

        </select>


        <select name="servico_id" required>

            <option value="">
                Selecione o serviço
            </option>

            <?php while ($servico = $servicos->fetch_assoc()): ?>

                <option value="<?= $servico['id'] ?>">

                    <?= htmlspecialchars($servico['nome']) ?>

                </option>

            <?php endwhile; ?>

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


        <select name="status" required>

            <option value="Agendado">
                Agendado
            </option>

            <option value="Confirmado">
                Confirmado
            </option>

            <option value="Concluído">
                Concluído
            </option>

            <option value="Cancelado">
                Cancelado
            </option>

        </select>


        <input
            type="text"
            name="observacao"
            placeholder="Observação (opcional)"
        >


        <button type="submit">
            Cadastrar agendamento
        </button>


        <a href="agendamentos.php" class="voltar">
            ← Voltar
        </a>

    </form>

</div>

</body>

</html>