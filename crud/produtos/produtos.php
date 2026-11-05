<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') {
    header("Location: /login.php");
    exit;
}

require_once "../../config/conexao.php";

$sql = "SELECT * FROM produtos ORDER BY id DESC";
$produtos = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Produtos - PetLove</title>
<link rel="stylesheet" href="../../assets/css/crud-produtos.css">
</head>
<body>

<div class="container">

<h1>Pet<span>Love</span></h1>
<h2>Produtos cadastrados</h2>
<p class="descricao">Consulte e gerencie os produtos disponíveis no PetLove.</p>

<div class="acoes">
    <a href="adicionar.php" class="adicionar">Adicionar produto</a>
    <a href="/chefe/index.php" class="voltar">Voltar ao início</a>
</div>

<div class="produtos">

<?php if ($produtos && $produtos->num_rows > 0): ?>
    <?php while ($produto = $produtos->fetch_assoc()): ?>

        <div class="produto">

            <?php if (!empty($produto['imagem'])): ?>
                <img src="/img/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
            <?php else: ?>
                <div class="sem-imagem">Sem imagem</div>
            <?php endif; ?>

            <h3><?= htmlspecialchars($produto['nome']) ?></h3>

            <p><strong>Categoria:</strong> <?= htmlspecialchars($produto['categoria']) ?></p>

            <p><strong>Descrição:</strong> <?= htmlspecialchars($produto['descricao'] ?? 'Não informada') ?></p>

            <p><strong>Estoque:</strong> <?= (int) $produto['estoque'] ?> unidades</p>

            <p class="preco"><strong>Preço:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>

            <div class="botoes">
                <a href="editar.php?id=<?= $produto['id'] ?>" class="editar">Editar</a>
                <a href="excluir.php?id=<?= $produto['id'] ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
            </div>

        </div>

    <?php endwhile; ?>
<?php else: ?>

    <div class="nenhum">
        <h3>Nenhum produto cadastrado</h3>
        <p>Ainda não existem produtos cadastrados.</p>
    </div>

<?php endif; ?>

</div>

</div>

</body>
</html>