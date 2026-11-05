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
            agendamentos.id,
            animais.nome AS pet,
            clientes.nome AS dono,
            servicos.nome AS servico,
            agendamentos.tipo_banho,
            agendamentos.tipo_tosa,
            agendamentos.data,
            agendamentos.horario,
            agendamentos.status,
            agendamentos.observacao

        FROM agendamentos

        INNER JOIN animais
            ON agendamentos.animal_id = animais.id

        INNER JOIN clientes
            ON animais.cliente_id = clientes.id

        INNER JOIN servicos
            ON agendamentos.servico_id = servicos.id

        ORDER BY agendamentos.data, agendamentos.horario";

$agendamentos = $conexao->query($sql);

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agendamentos - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/crud-agendamentos.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <h2>Agendamentos</h2>

    <p class="descricao">
        Consulte e gerencie os agendamentos dos clientes.
    </p>


    <div class="acoes">

        <a href="adicionar_agendamento.php" class="adicionar">
            Adicionar agendamento
        </a>

        <a href="<?= $inicio ?>" class="voltar">
            ← Voltar ao início
        </a>

    </div>


    <div class="agendamentos">

        <?php if ($agendamentos && $agendamentos->num_rows > 0): ?>

            <?php while ($agendamento = $agendamentos->fetch_assoc()): ?>

                <div class="agendamento">

                    <h3>
                        <?= htmlspecialchars($agendamento['servico']) ?>
                    </h3>


                    <p>
                        <strong>Pet:</strong>
                        <?= htmlspecialchars($agendamento['pet']) ?>
                    </p>


                    <p>
                        <strong>Dono:</strong>
                        <?= htmlspecialchars($agendamento['dono']) ?>
                    </p>


                    <p>
                        <strong>Data:</strong>
                        <?= date('d/m/Y', strtotime($agendamento['data'])) ?>
                    </p>


                    <p>
                        <strong>Horário:</strong>
                        <?= htmlspecialchars(substr($agendamento['horario'], 0, 5)) ?>
                    </p>


                    <?php if (!empty($agendamento['tipo_banho'])): ?>

                        <p>
                            <strong>Tipo de banho:</strong>
                            <?= htmlspecialchars($agendamento['tipo_banho']) ?>
                        </p>

                    <?php endif; ?>


                    <?php if (!empty($agendamento['tipo_tosa'])): ?>

                        <p>
                            <strong>Tipo de tosa:</strong>
                            <?= htmlspecialchars($agendamento['tipo_tosa']) ?>
                        </p>

                    <?php endif; ?>


                    <p>
                        <strong>Status:</strong>
                        <?= htmlspecialchars($agendamento['status']) ?>
                    </p>


                    <?php if (!empty($agendamento['observacao'])): ?>

                        <p>
                            <strong>Observação:</strong>
                            <?= htmlspecialchars($agendamento['observacao']) ?>
                        </p>

                    <?php endif; ?>


                    <div class="botoes">

                        <a
                            href="editar_agendamento.php?id=<?= $agendamento['id'] ?>"
                            class="editar"
                        >
                            Editar
                        </a>


                        <a
                            href="excluir_agendamento.php?id=<?= $agendamento['id'] ?>"
                            class="excluir"
                            onclick="return confirm('Tem certeza que deseja excluir este agendamento?')"
                        >
                            Excluir
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="nenhum">

                <h3>Nenhum agendamento encontrado</h3>

                <p>
                    Ainda não existem agendamentos cadastrados.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>