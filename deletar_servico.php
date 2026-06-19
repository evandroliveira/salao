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

$idBruto = $_POST['id_servico'] ?? $_POST['id'] ?? null;
$id = filter_var($idBruto, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if ($id === false) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID invalido.'
    ]);
    exit;
}

try {
    $stmt = $conexao->prepare('DELETE FROM servicos WHERE id_servico = :id');
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Serviço nao encontrado.'
        ]);
        exit;
    }

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Serviço removido com sucesso.'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao deletar serviço: ' . $e->getMessage()
    ]);
}