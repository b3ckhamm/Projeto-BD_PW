<?php

session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') {
    header("Location: /login.php");
    exit;
}

require_once "../../config/conexao.php";


$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: vendas.php");
    exit;
}


$sql = "SELECT
            vendas.id AS venda_id,
            clientes.nome AS cliente,
            vendas.data,
            vendas.total,
            produtos.nome AS produto,
            itens_venda.quantidade,
            itens_venda.preco_unitario,
            (itens_venda.quantidade * itens_venda.preco_unitario) AS subtotal

        FROM vendas

        INNER JOIN clientes
            ON vendas.cliente_id = clientes.id

        INNER JOIN itens_venda
            ON vendas.id = itens_venda.venda_id

        INNER JOIN produtos
            ON itens_venda.produto_id = produtos.id

        WHERE vendas.id = ?

        ORDER BY produtos.nome";


$stmt = $conexao->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detalhes da Venda - PetLove</title>

    <link rel="stylesheet" href="../../assets/css/vendas.css">

</head>

<body>

<div class="container">

    <h1>Pet<span>Love</span></h1>

    <h2>Detalhes da venda</h2>


    <?php if ($resultado->num_rows > 0): ?>

        <?php $primeiro = $resultado->fetch_assoc(); ?>

        <div class="resumo">

            <p>
                <strong>Venda:</strong>
                #<?= $primeiro['venda_id'] ?>
            </p>

            <p>
                <strong>Cliente:</strong>
                <?= htmlspecialchars($primeiro['cliente']) ?>
            </p>

            <p>
                <strong>Data:</strong>
                <?= date('d/m/Y H:i', strtotime($primeiro['data'])) ?>
            </p>

        </div>


        <div class="produtos-venda">

            <div class="produto-venda">

                <h3>
                    <?= htmlspecialchars($primeiro['produto']) ?>
                </h3>

                <p>
                    <strong>Quantidade:</strong>
                    <?= $primeiro['quantidade'] ?>
                </p>

                <p>
                    <strong>Preço unitário:</strong>
                    R$
                    <?= number_format($primeiro['preco_unitario'], 2, ',', '.') ?>
                </p>

                <p>
                    <strong>Subtotal:</strong>
                    R$
                    <?= number_format($primeiro['subtotal'], 2, ',', '.') ?>
                </p>

            </div>


            <?php while ($produto = $resultado->fetch_assoc()): ?>

                <div class="produto-venda">

                    <h3>
                        <?= htmlspecialchars($produto['produto']) ?>
                    </h3>

                    <p>
                        <strong>Quantidade:</strong>
                        <?= $produto['quantidade'] ?>
                    </p>

                    <p>
                        <strong>Preço unitário:</strong>
                        R$
                        <?= number_format($produto['preco_unitario'], 2, ',', '.') ?>
                    </p>

                    <p>
                        <strong>Subtotal:</strong>
                        R$
                        <?= number_format($produto['subtotal'], 2, ',', '.') ?>
                    </p>

                </div>

            <?php endwhile; ?>

        </div>


        <div class="total-final">

            <strong>Total da venda:</strong>

            R$
            <?= number_format($primeiro['total'], 2, ',', '.') ?>

        </div>


    <?php else: ?>

        <div class="nenhuma">

            <h3>Venda não encontrada</h3>

            <p>
                Não foi possível encontrar os dados dessa venda.
            </p>

        </div>

    <?php endif; ?>


    <div class="acoes">

        <a href="vendas.php" class="voltar">
            ← Voltar para vendas
        </a>

    </div>

</div>

</body>

</html>