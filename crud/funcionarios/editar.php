<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'chefe') {
    header("Location: /login.php");
    exit;
}

require_once "../../config/conexao.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: funcionarios.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| BUSCAR FUNCIONÁRIO
|--------------------------------------------------------------------------
*/

$stmt = $conexao->prepare(
    "SELECT * FROM funcionarios WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$funcionario = $resultado->fetch_assoc();

if (!$funcionario) {
    header("Location: funcionarios.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| ATUALIZAR FUNCIONÁRIO
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $salario = (float) str_replace(',', '.', $_POST["salario"]);

    if (!empty($senha)) {

        $sql = "UPDATE funcionarios SET nome = ?, email = ?, senha = ?, salario = ? WHERE id = ?";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "sssdi",
            $nome,
            $email,
            $senha,
            $salario,
            $id
        );

    } else {

        $sql = "UPDATE funcionarios SET nome = ?, email = ?, salario = ? WHERE id = ?";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "ssdi",
            $nome,
            $email,
            $salario,
            $id
        );
    }

    $stmt->execute();

    header("Location: funcionarios.php");
    exit;
}
?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Editar Funcionário - PetLove</title>

<link
    rel="stylesheet"
    href="../../assets/css/crud-funcionarios.css"
>

</head>

<body>

<div class="container">

<h1>
    Pet<span>Love</span>
</h1>

<h2>
    Editar funcionário
</h2>


<form method="POST">

    <input
        type="text"
        name="nome"
        value="<?= htmlspecialchars($funcionario['nome']) ?>"
        placeholder="Nome do funcionário"
        required
    >


    <input
        type="email"
        name="email"
        value="<?= htmlspecialchars($funcionario['email']) ?>"
        placeholder="E-mail"
        required
    >


    <input
        type="password"
        name="senha"
        placeholder="Nova senha (deixe vazio para manter)"
    >


    <input
        type="number"
        name="salario"
        value="<?= htmlspecialchars($funcionario['salario'] ?? '') ?>"
        placeholder="Salário (R$)"
        step="0.01"
        min="0"
        required
    >


    <button type="submit">
        Salvar alterações
    </button>


     <a href="funcionarios.php" class="voltar">
        ← Voltar
    </a>

</form>

</div>

</body>

</html>