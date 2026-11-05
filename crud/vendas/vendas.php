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

$sql = "SELECT
            vendas.id,
            clientes.nome AS cliente,
            vendas.data,
            vendas.total
        FROM vendas

        INNER JOIN clientes
            ON vendas.cliente_id = clientes.id

        ORDER BY vendas.data DESC";

$vendas = $conexao->query($sql);

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vendas - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/vendas.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <h2>Vendas realizadas</h2>

    <p class="descricao">
        Consulte as vendas registradas no sistema.
    </p>

    <div class="acoes">

        <a href="<?= $pagina_inicial ?>" class="voltar">
            ← Voltar ao início
        </a>

    </div>

    <div class="vendas">

        <?php if ($vendas && $vendas->num_rows > 0): ?>

            <?php while ($venda = $vendas->fetch_assoc()): ?>

                <div class="venda">

                    <h3>
                        Venda #<?= $venda['id'] ?>
                    </h3>

                    <p>
                        <strong>Cliente:</strong>
                        <?= htmlspecialchars($venda['cliente']) ?>
                    </p>

                    <p>
                        <strong>Data:</strong>
                        <?= date('d/m/Y H:i', strtotime($venda['data'])) ?>
                    </p>

                    <p class="total">
                        <strong>Total:</strong>
                        R$
                        <?= number_format($venda['total'], 2, ',', '.') ?>
                    </p>

                    <a
                        href="venda_detalhes.php?id=<?= $venda['id'] ?>"
                        class="detalhes"
                    >
                        Ver produtos
                    </a>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="nenhuma">

                <h3>Nenhuma venda encontrada</h3>

                <p>
                    Ainda não existem vendas registradas no sistema.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>