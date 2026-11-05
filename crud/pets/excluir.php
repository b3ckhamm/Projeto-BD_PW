<?php

require_once "../../config/conexao.php";

$id = $_GET["id"] ?? null;

if ($id) {

    $stmt = $conexao->prepare(
        "DELETE FROM animais WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();
}

header("Location: pets.php");
exit;