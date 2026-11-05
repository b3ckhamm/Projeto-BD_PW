<?php

session_start();

if (
    !isset($_SESSION['tipo_usuario']) ||
    $_SESSION['tipo_usuario'] !== 'cliente'
) {
    header('Location: /login.php');
    exit;
}

$nome = $_SESSION['usuario_nome'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Área do Cliente - PetLove</title>

    <link
        rel="stylesheet"
        href="/assets/css/chefe.css"
    >

    <link
        rel="stylesheet"
        href="/assets/css/cliente.css"
    >

</head>

<body>

    <header>

        <h1>
            PetLove
        </h1>

        <nav>

            <a href="/cliente/index.php">
                Início
            </a>

            <a href="/produtos.php">
                Produtos
            </a>

            <a href="/logout.php">
                Sair
            </a>

        </nav>

    </header>

    <section class="banner">

        <h2>
            Área do Cliente
        </h2>

        <p>
            Bem-vindo, <?= htmlspecialchars($nome) ?>!
        </p>

        <p>
            Acesse os serviços e informações do seu pet.
        </p>

    </section>

    <main>

        <h2>
            Menu do Cliente
        </h2>

        <div class="cards">

            <a href="/produtos.php" class="card">
                <h3>Produtos</h3>
                <p>Confira os produtos disponíveis para seu pet.</p>
            </a>

            <a href="/cliente/pets.php" class="card">
                <h3>Meus Pets</h3>
                <p>Consulte os pets cadastrados na sua conta.</p>
            </a>

            <a href="/cliente/agendamentos.php" class="card">
                <h3>Agendamentos</h3>
                <p>Consulte seus agendamentos.</p>
            </a>

            <a href="/cliente/compras.php" class="card">
                <h3>Minhas Compras</h3>
                <p>Consulte suas compras realizadas.</p>
            </a>

        </div>

    </main>

    <footer>

        <p>
            2026 PetLove - Área do Cliente
        </p>

    </footer>

</body>

</html>