<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Metodo nao permitido.'
    ]);
    exit;
}

if (!$conexao) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Falha na conexao com o banco de dados.'
    ]);
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID invalido.'
    ]);
    exit;
}

try {
    $stmt = $conexao->prepare('DELETE FROM clientes WHERE id = :id');
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Cliente nao encontrado.'
        ]);
        exit;
    }

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Cliente removido com sucesso.'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao deletar cliente: ' . $e->getMessage()
    ]);
}
