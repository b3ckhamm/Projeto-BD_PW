<?php

require_once __DIR__ . "/../config/conexao.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "SELECT * FROM produtos WHERE id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Produto não encontrado.");
}

$produto = $resultado->fetch_assoc();

$nome = $produto['nome'];
$preco = $produto['preco'];
$imagem = $produto['imagem'];

$chave_pix = "petlove@teste.com";

$confirmado = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $confirmado = true;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pagamento - PetLove</title>

    <link rel="stylesheet" href="pix.css">

    <style>
        html,
        body {
            min-height: 100%;
        }

        body {
            background-image: url("/img/pix.jpg") !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-attachment: fixed !important;
        }
    </style>

</head>

<body>

<div class="container">

    <h1>Pagamento via Pix</h1>

    <p>
        Confira os dados do seu pedido.
    </p>

    <div class="produto">

        <?php if (!empty($imagem)): ?>

            <img
                src="/img/<?php echo htmlspecialchars($imagem); ?>"
                alt="<?php echo htmlspecialchars($nome); ?>"
            >

        <?php endif; ?>

        <h2>
            <?php echo htmlspecialchars($nome); ?>
        </h2>

        <p class="preco">
            Valor a pagar:

            <strong>
                R$ <?php echo number_format($preco, 2, ',', '.'); ?>
            </strong>
        </p>

    </div>

    <h2>Pagamento via Pix</h2>

    <p>
        Escaneie o QR Code ou copie a chave Pix.
    </p>

    <div class="qrcode">

        <img
            src="qr.jpg"
            alt="QR Code Pix"
        >

    </div>

    <p class="chave">
        Chave Pix
    </p>

    <div class="codigo">

        <input
            type="text"
            value="<?php echo htmlspecialchars($chave_pix); ?>"
            id="pix"
            readonly
        >

        <button
            type="button"
            onclick="copiarPix()"
        >
            Copiar
        </button>

    </div>

    <form method="POST">

        <button
            type="submit"
            class="confirmar"
        >
            Confirmar pagamento
        </button>

    </form>

    <?php if ($confirmado): ?>

        <div class="sucesso">
            Pagamento Pix selecionado com sucesso!
        </div>

    <?php endif; ?>

    <a
        href="../produtos.php"
        class="voltar"
    >
        Voltar
    </a>

</div>

<script>
function copiarPix() {
    const pix = document.getElementById("pix");

    navigator.clipboard.writeText(pix.value);

    alert("Chave Pix copiada!");
}
</script>

</body>

</html>