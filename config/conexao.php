<?php

$conexao = new mysqli(
    "sql207.infinityfree.com",
    "if0_42680530",
    "4qvvBr5T7i1Orgr",
    "if0_42680530_database"
);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");