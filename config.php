<?php
// dados de acesso ao banco de dados

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "salao";

// criando a conexao com o banco de dados via PDO
try {
    $dsn = "mysql:host={$host};dbname={$banco};charset=utf8mb4";
    $conexao = new PDO($dsn, $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $mensagem = "Conexao bem-sucedida!";
} catch (PDOException $e) {
    $conexao = null;
    $mensagem = "Erro de conexao: " . $e->getMessage();
}