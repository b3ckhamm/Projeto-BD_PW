<?php

session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') {
    header('Location: /login.php');
    exit;
}

require_once "../../config/conexao.php";

$mensagem = '';

// Salvar alteração
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];
    $descricao_completa = trim($_POST['descricao_completa']);

    $stmt = $conexao->prepare("UPDATE produtos SET descricao_completa = ? WHERE id = ?");
    $stmt->bind_param("si", $descricao_completa, $id);
    $stmt->execute();

    $mensagem = 'Descrição atualizada com sucesso!';
}

// Buscar todos os produtos
$sql = "SELECT * FROM produtos ORDER BY categoria, id";
$resultado = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Descrições - PetLove</title>
    <link rel="stylesheet" href="../../assets/css/descricoes-crud.css">
</head>

<body>

<header>
    <h1>PetLove</h1>
    <nav>
        <a href="/chefe/index.php">Voltar ao Menu</a>
        <a href="/logout.php">Sair</a>
    </nav>
</header>

<section class="conteudo">

    <h2>Editar Descrições dos Produtos</h2>

    <?php if ($mensagem): ?>
        <p class="sucesso"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <div class="lista-produtos">

        <?php while ($produto = $resultado->fetch_assoc()): ?>

            <form method="POST" class="item-produto">

                <input type="hidden" name="id" value="<?= $produto['id'] ?>">

                <h3><?= htmlspecialchars($produto['nome']) ?></h3>

                <textarea name="descricao_completa" rows="4"><?= htmlspecialchars($produto['descricao_completa'] ?? '') ?></textarea>

                <button type="submit">Salvar</button>

            </form>

        <?php endwhile; ?>

    </div>

</section>

</body>
</html>