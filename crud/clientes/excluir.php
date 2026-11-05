<?php

require_once "../../config/conexao.php";

$id = $_GET["id"] ?? null;

if ($id) {

    $stmt = $conexao->prepare(
        "DELETE FROM clientes WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();
}

header("Location: clientes.php");
exit;
