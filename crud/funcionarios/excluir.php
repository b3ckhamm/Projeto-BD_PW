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
| EXCLUIR FUNCIONÁRIO
|--------------------------------------------------------------------------
*/

$stmt = $conexao->prepare(
    "DELETE FROM funcionarios WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();


header("Location: funcionarios.php");
exit;

?>
