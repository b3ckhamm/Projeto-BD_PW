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
        vendas.id AS venda_id,
        vendas.data,
        vendas.total,
        produtos.nome AS produto,
        produtos.imagem,
        itens_venda.quantidade,
        itens_venda.preco_unitario,
        (itens_venda.quantidade * itens_venda.preco_unitario) AS subtotal
    FROM vendas
    INNER JOIN itens_venda
        ON vendas.id = itens_venda.venda_id
    INNER JOIN produtos
        ON itens_venda.produto_id = produtos.id
    WHERE vendas.cliente_id = ?
    ORDER BY vendas.data DESC, vendas.id DESC
";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();

$resultado = $stmt->get_result();

$compras = [];

while ($item = $resultado->fetch_assoc()) {

    $venda_id = $item['venda_id'];

    if (!isset($compras[$venda_id])) {
        $compras[$venda_id] = [
            'data' => $item['data'],
            'total' => $item['total'],
            'itens' => []
        ];
    }

    $compras[$venda_id]['itens'][] = $item;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Minhas Compras - PetLove</title>

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

        <h2>Minhas Compras</h2>

        <p class="descricao">
            Consulte o histórico de compras realizadas na sua conta.
        </p>

        <div class="acoes">

            <a
                href="/cliente/index.php"
                class="voltar"
            >
                ← Voltar
            </a>

        </div>

        <div class="pets">

            <?php if (count($compras) > 0): ?>

                <?php foreach ($compras as $venda_id => $compra): ?>

                    <div class="pet">

                        <h3>
                            Compra #<?= htmlspecialchars($venda_id) ?>
                        </h3>

                        <p>
                            <strong>Data:</strong>
                            <?= date(
                                'd/m/Y H:i',
                                strtotime($compra['data'])
                            ) ?>
                        </p>

                        <hr>

                        <?php foreach ($compra['itens'] as $item): ?>

                            <div class="item-compra">

                                <p>
                                    <strong>Produto:</strong>
                                    <?= htmlspecialchars($item['produto']) ?>
                                </p>

                                <p>
                                    <strong>Quantidade:</strong>
                                    <?= htmlspecialchars($item['quantidade']) ?>
                                </p>

                                <p>
                                    <strong>Preço unitário:</strong>
                                    R$
                                    <?= number_format(
                                        $item['preco_unitario'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </p>

                                <p>
                                    <strong>Subtotal:</strong>
                                    R$
                                    <?= number_format(
                                        $item['subtotal'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </p>

                            </div>

                        <?php endforeach; ?>

                        <hr>

                        <p>
                            <strong>Total da compra:</strong>

                            <span class="total-compra">
                                R$
                                <?= number_format(
                                    $compra['total'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>
                            </span>
                        </p>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="pet">

                    <h3>
                        Nenhuma compra encontrada
                    </h3>

                    <p>
                        Você ainda não possui compras realizadas.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</body>

</html>