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
        agendamentos.id,
        animais.nome AS pet,
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
    INNER JOIN servicos
        ON agendamentos.servico_id = servicos.id
    WHERE animais.cliente_id = ?
    ORDER BY agendamentos.data, agendamentos.horario
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

    <title>Agendamentos - PetLove</title>

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

        <h2>Meus Agendamentos</h2>

        <p class="descricao">
            Consulte e gerencie os serviços agendados para seus pets.
        </p>

        <div class="acoes">

            <a
                href="/cliente/adicionar_agendamento.php"
                class="adicionar"
            >
                Agendar serviço
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

                <?php while ($agendamento = $resultado->fetch_assoc()): ?>

                    <div class="pet">

                        <h3>
                            <?= htmlspecialchars($agendamento['servico']) ?>
                        </h3>

                        <p>
                            <strong>Pet:</strong>
                            <?= htmlspecialchars($agendamento['pet']) ?>
                        </p>

                        <p>
                            <strong>Data:</strong>
                            <?= date(
                                'd/m/Y',
                                strtotime($agendamento['data'])
                            ) ?>
                        </p>

                        <p>
                            <strong>Horário:</strong>
                            <?= htmlspecialchars(
                                substr($agendamento['horario'], 0, 5)
                            ) ?>
                        </p>

                        <?php if (!empty($agendamento['tipo_banho'])): ?>

                            <p>
                                <strong>Tipo de banho:</strong>
                                <?= htmlspecialchars(
                                    $agendamento['tipo_banho']
                                ) ?>
                            </p>

                        <?php endif; ?>

                        <?php if (!empty($agendamento['tipo_tosa'])): ?>

                            <p>
                                <strong>Tipo de tosa:</strong>
                                <?= htmlspecialchars(
                                    $agendamento['tipo_tosa']
                                ) ?>
                            </p>

                        <?php endif; ?>

                        <p>
                            <strong>Status:</strong>
                            <?= htmlspecialchars(
                                $agendamento['status']
                            ) ?>
                        </p>

                        <?php if (!empty($agendamento['observacao'])): ?>

                            <p>
                                <strong>Observação:</strong>
                                <?= htmlspecialchars(
                                    $agendamento['observacao']
                                ) ?>
                            </p>

                        <?php endif; ?>

                        <div class="botoes">

                            <a
                                href="/cliente/editar_agendamento.php?id=<?= $agendamento['id'] ?>"
                                class="editar"
                            >
                                Editar
                            </a>

                            <a
                                href="/cliente/excluir_agendamento.php?id=<?= $agendamento['id'] ?>"
                                class="excluir"
                                onclick="return confirm('Tem certeza que deseja excluir este agendamento?')"
                            >
                                Excluir
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="pet">

                    <h3>Nenhum agendamento encontrado</h3>

                    <p>
                        Você ainda não possui agendamentos.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</body>

</html>