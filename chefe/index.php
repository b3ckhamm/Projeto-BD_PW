<?php

session_start();

if (
    !isset($_SESSION['tipo_usuario']) ||
    $_SESSION['tipo_usuario'] !== 'chefe'
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

    <title>Área do Chefe - PetLove</title>

    <link
        rel="stylesheet"
        href="../../assets/css/chefe.css"
    >

</head>

<body>

    <header>

        <h1>PetLove</h1>

        <nav>

            <a href="/chefe/index.php">
                Início
            </a>

            <a href="/logout.php">
                Sair
            </a>

        </nav>

    </header>

    <section class="banner">

        <h2>Área do Chefe</h2>

        <p>
            Bem-vindo, <?= htmlspecialchars($nome) ?>!
        </p>

        <p>
            Gerencie as principais áreas do PetLove.
        </p>

    </section>

    <main>

        <h2>Menu Administrativo</h2>

        <div class="cards">

            <a
                href="/crud/clientes/clientes.php"
                class="card"
            >

                <h3>Clientes</h3>

                <p>
                    Cadastrar, editar e excluir clientes.
                </p>

            </a>

            <a
                href="/crud/pets/pets.php"
                class="card"
            >

                <h3>Pets</h3>

                <p>
                    Cadastrar, editar e excluir pets.
                </p>

            </a>

            <a
                href="/crud/produtos/produtos.php"
                class="card"
            >

                <h3>Produtos</h3>

                <p>
                    Cadastrar, editar e excluir produtos.
                </p>

            </a>

            <a
                href="/crud/produtos/descricoes.php"
                class="card"
            >

                <h3>Descrições dos Produtos</h3>

                <p>
                    Editar a descrição detalhada de cada produto.
                </p>

            </a>

            <a
                href="/crud/servicos/servicos.php"
                class="card"
            >

                <h3>Serviços</h3>

                <p>
                    Cadastrar, editar e excluir serviços.
                </p>

            </a>

            <a
                href="/crud/funcionarios/funcionarios.php"
                class="card"
            >

                <h3>Funcionários</h3>

                <p>
                    Cadastrar, editar e excluir funcionários.
                </p>

            </a>

            <a
                href="/crud/agendamentos/agendamentos.php"
                class="card"
            >

                <h3>Agendamentos</h3>

                <p>
                    Visualizar e gerenciar agendamentos.
                </p>

            </a>

            <a
                href="/crud/vendas/vendas.php"
                class="card"
            >

                <h3>Vendas</h3>

                <p>
                    Visualizar as vendas realizadas.
                </p>

            </a>

        </div>

    </main>

    <footer>

        <p>
            2026 PetLove - Área do Chefe
        </p>

    </footer>

</body>

</html>