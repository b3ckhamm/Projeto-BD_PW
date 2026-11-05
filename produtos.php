<?php

session_start();

require_once __DIR__ . "/config/conexao.php";

$cliente_logado = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'cliente';

$sql = "SELECT * FROM produtos ORDER BY categoria, id";
$resultado = $conexao->query($sql);

$categorias = [];

if ($resultado) {
    while ($produto = $resultado->fetch_assoc()) {
        $categoria = $produto['categoria'];

        if (!isset($categorias[$categoria])) {
            $categorias[$categoria] = [];
        }

        $categorias[$categoria][] = $produto;
    }
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Produtos - PetLove</title>

<link rel="stylesheet" href="/assets/css/produtos.css">

</head>

<body>

<header>

<h1>PetLove</h1>

<nav>

    <?php if ($cliente_logado): ?>

        <a href="/cliente/index.php">
            Área do Cliente
        </a>

        <a href="/logout.php">
            Sair
        </a>

    <?php else: ?>

        <a href="/login.php">
            Entrar
        </a>

        <a href="/cadastro.php">
            Cadastrar
        </a>

    <?php endif; ?>

    <a href="/contato.html">
        Contato
    </a>

</nav>

</header>

<section class="banner">

<h2>Produtos para seu pet</h2>

<p>
    Tudo que seu melhor amigo precisa.
</p>

</section>

<section class="produtos">

<h2>Categorias</h2>

<div class="categorias">

    <?php foreach ($categorias as $categoria => $lista): ?>

        <a href="#<?php echo strtolower(str_replace(' ', '-', $categoria)); ?>">

            <?php echo htmlspecialchars($categoria); ?>

        </a>

    <?php endforeach; ?>

</div>

<?php foreach ($categorias as $categoria => $lista): ?>

    <h2 id="<?php echo strtolower(str_replace(' ', '-', $categoria)); ?>">

        <?php echo htmlspecialchars($categoria); ?>

    </h2>

    <div class="cards">

        <?php foreach ($lista as $produto): ?>

            <a
                href="/descricao.php?id=<?php echo (int) $produto['id']; ?>"
                class="produto-link"
            >

                <div class="card">

                    <?php if (!empty($produto['imagem'])): ?>

                        <img
                            src="/img/<?php echo htmlspecialchars($produto['imagem']); ?>"
                            alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                        >

                    <?php else: ?>

                        <p>Imagem não disponível</p>

                    <?php endif; ?>

                    <h3>
                        <?php echo htmlspecialchars($produto['nome']); ?>
                    </h3>

                    <p>
                        <?php echo htmlspecialchars($produto['descricao']); ?>
                    </p>

                    <span>
                        R$
                        <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                    </span>

                </div>

            </a>

        <?php endforeach; ?>

    </div>

<?php endforeach; ?>

</section>

<footer>

<p>
    2026 PetLove - Todos os direitos reservados.
</p>

</footer>

</body>

</html>