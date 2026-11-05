<?php session_start(); if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') { header("Location: /login.php"); exit; } require_once "../../config/conexao.php"; $sql = "SELECT * FROM servicos ORDER BY id DESC"; $servicos = $conexao->query($sql); ?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Serviços - PetLove</title>

<link
    rel="stylesheet"
 <link rel="stylesheet" href="../../assets/css/crud-servicos.css">

</head>

<body>

<div class="container">

<h1>Pet<span>Love</span></h1>

<h2>Serviços cadastrados</h2>

<p class="descricao">
    Consulte e gerencie os serviços oferecidos pelo PetLove.
</p>


<div class="acoes">

    <a
        href="adicionar.php"
        class="adicionar"
    >
        Adicionar serviço
    </a>


    <a
        href="/chefe/index.php"
        class="voltar"
    >
        Voltar ao início
    </a>

</div>


<div class="servicos">

    <?php if ($servicos && $servicos->num_rows > 0): ?>

        <?php while ($servico = $servicos->fetch_assoc()): ?>

            <div class="servico">

                <h3>
                    <?= htmlspecialchars($servico['nome']) ?>
                </h3>


                <p>
                    <strong>Descrição:</strong>

                    <?= htmlspecialchars(
                        $servico['descricao'] ?? 'Não informada'
                    ) ?>
                </p>


                <p class="preco">

                    <strong>Preço:</strong>

                    R$
                    <?= number_format(
                        $servico['preco'],
                        2,
                        ',',
                        '.'
                    ) ?>

                </p>


                <div class="botoes">

                    <a
                        href="editar.php?id=<?= $servico['id'] ?>"
                        class="editar"
                    >
                        Editar
                    </a>


                    <a
                        href="excluir.php?id=<?= $servico['id'] ?>"
                        class="excluir"
                        onclick="return confirm('Tem certeza que deseja excluir este serviço?')"
                    >
                        Excluir
                    </a>

                </div>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="servico">

            <h3>
                Nenhum serviço cadastrado
            </h3>

            <p>
                Ainda não existem serviços cadastrados.
            </p>

        </div>

    <?php endif; ?>

</div>

</div>

</body>

</html>