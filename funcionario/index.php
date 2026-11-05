<?php

session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'funcionario') {
    header('Location: /login.php');
    exit;
}

$nome = $_SESSION['usuario_nome'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Área do Funcionário - PetLove</title>

    <link rel="stylesheet" href="/assets/css/chefe.css">

</head>

<body>

<header>

    <h1>PetLove</h1>

    <nav>

        <a href="/funcionario/index.php">
            Início
        </a>

        <a href="/logout.php">
            Sair
        </a>

    </nav>

</header>


<section class="banner">

    <h2>Área do Funcionário</h2>

    <p>
        Bem-vindo, <?= htmlspecialchars($nome) ?>!
    </p>

    <p>
        Gerencie os atendimentos e informações do PetLove.
    </p>

</section>


<main>

    <h2>Menu do Funcionário</h2>

    <div class="cards">


        <a href="/crud/clientes/clientes.php" class="card">

            <h3>Clientes</h3>

            <p>
                Consultar, cadastrar, editar e gerenciar clientes.
            </p>

        </a>


        <a href="/crud/pets/pets.php" class="card">

            <h3>Pets</h3>

            <p>
                Consultar e gerenciar os pets cadastrados.
            </p>

        </a>


        <a href="/crud/agendamentos/agendamentos.php" class="card">

            <h3>Agendamentos</h3>

            <p>
                Visualizar e gerenciar os agendamentos dos clientes.
            </p>

        </a>


        <a href="/crud/vendas/vendas.php" class="card">

            <h3>Vendas</h3>

            <p>
                Visualizar as vendas realizadas no PetLove.
            </p>

        </a>


    </div>

</main>


<footer>

    <p>
        2026 PetLove - Área do Funcionário
    </p>

</footer>

</body>

</html>
