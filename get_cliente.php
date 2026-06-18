<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';

if (!$conexao) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Falha na conexao com o banco de dados.'
    ]);
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID invalido.'
    ]);
    exit;
}

try {
    $stmt = $conexao->prepare('SELECT id, nome, telefone, email FROM clientes WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $cliente = $stmt->fetch();

    if (!$cliente) {
        http_response_code(404);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Cliente nao encontrado.'
        ]);
        exit;
    }

    echo json_encode($cliente);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao buscar cliente: ' . $e->getMessage()
    ]);
}
