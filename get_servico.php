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

$idBruto = $_GET['id_servico'] ?? $_GET['id'] ?? null;
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
    $stmt = $conexao->prepare('SELECT id_servico, nome_servico, preco, duracao_minutos FROM servicos WHERE id_servico = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $servico = $stmt->fetch();

    if (!$servico) {
        http_response_code(404);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Serviço nao encontrado.'
        ]);
        exit;
    }

    echo json_encode([
        'id_servico' => (int) $servico['id_servico'],
        'nome_servico' => $servico['nome_servico'],
        'preco' => (float) $servico['preco'],
        'duracao_minutos' => (int) $servico['duracao_minutos'],
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao buscar serviço: ' . $e->getMessage()
    ]);
}