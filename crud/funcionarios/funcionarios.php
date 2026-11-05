<?php session_start(); if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') { header("Location: /login.php"); exit; } require_once "../../config/conexao.php"; $sql = "SELECT id, nome, email FROM funcionarios ORDER BY id DESC"; $funcionarios = $conexao->query($sql); ?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Funcionários - PetLove</title>

<link
    rel="stylesheet"
    href="../../assets/css/crud-funcionarios.css"
>

</head>

<body>

<div class="container">

<h1>Pet<span>Love</span></h1>

<h2>Funcionários cadastrados</h2>

<p class="descricao">
    Consulte e gerencie os funcionários do PetLove.
</p>


<div class="acoes">

    <a
        href="adicionar.php"
        class="adicionar"
    >
        Adicionar funcionário
    </a>


    <a
        href="/chefe/index.php"
        class="voltar"
    >
        Voltar ao início
    </a>

</div>


<div class="funcionarios">

    <?php if ($funcionarios && $funcionarios->num_rows > 0): ?>

        <?php while ($funcionario = $funcionarios->fetch_assoc()): ?>

            <div class="funcionario">

                <h3>
                    <?= htmlspecialchars($funcionario['nome']) ?>
                </h3>


                <p>
                    <strong>E-mail:</strong>
                    <?= htmlspecialchars($funcionario['email']) ?>
                </p>


                <div class="botoes">

                    <a
                        href="editar.php?id=<?= $funcionario['id'] ?>"
                        class="editar"
                    >
                        Editar
                    </a>


                    <a
                        href="excluir.php?id=<?= $funcionario['id'] ?>"
                        class="excluir"
                        onclick="return confirm('Tem certeza que deseja excluir este funcionário?')"
                    >
                        Excluir
                    </a>

                </div>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="funcionario">

            <h3>
                Nenhum funcionário cadastrado
            </h3>

            <p>
                Ainda não existem funcionários cadastrados.
            </p>

        </div>

    <?php endif; ?>

</div>

</div>

</body>

</html>