<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/config/conexao.php";

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

$logado = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'cliente';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($produto['nome']); ?> - PetLove</title>
<link rel="stylesheet" href="assets/css/descricao.css">
</head>
<body>

<header>
    <h1>PetLove</h1>
    <nav>
        <a href="index.html">Início</a>
        <a href="contato.html">Contato</a>
        <a href="login.php">Entrar</a>
    </nav>
</header>

<section class="descricao">
    <div class="produto-detalhe">
        <img src="/img/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">

        <div class="info">
            <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
            <p><?php echo htmlspecialchars($produto['descricao_completa']); ?></p>
            <span class="preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>

            <div class="acoes">
                <?php if ($logado): ?>
                    <a href="pagamento_sistema/pagamento.html?id=<?php echo $produto['id']; ?>" class="comprar">Comprar</a>
                <?php else: ?>
                    <a href="/login.php?redirect=/descricao.php?id=<?php echo $produto['id']; ?>" class="comprar">Comprar</a>
                <?php endif; ?>
                <a href="produtos.php" class="voltar">Voltar</a>
            </div>
        </div>
    </div>
</section>

<footer>
    <p>2026 PetLove - Todos os direitos reservados.</p>
</footer>

</body>
</html>