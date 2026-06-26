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

$id = isset($_POST['id_cliente'])
    ? (int) $_POST['id_cliente']
    : (isset($_POST['id']) ? (int) $_POST['id'] : 0);
$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($nome === '') {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'O nome do cliente e obrigatorio.'
    ]);
    exit;
}

try {
    if ($id > 0) {
        $stmt = $conexao->prepare('UPDATE clientes SET nome = :nome, telefone = :telefone, email = :email WHERE id_cliente = :id');
        $stmt->execute([
            ':nome' => $nome,
            ':telefone' => $telefone !== '' ? $telefone : null,
            ':email' => $email !== '' ? $email : null,
            ':id' => $id,
        ]);

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Cliente atualizado com sucesso.'
        ]);
        exit;
    }

    $stmt = $conexao->prepare('INSERT INTO clientes (nome, telefone, email) VALUES (:nome, :telefone, :email)');
    $stmt->execute([
        ':nome' => $nome,
        ':telefone' => $telefone !== '' ? $telefone : null,
        ':email' => $email !== '' ? $email : null,
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Cliente cadastrado com sucesso.',
        'id' => (int) $conexao->lastInsertId(),
        'id_cliente' => (int) $conexao->lastInsertId()
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao salvar cliente: ' . $e->getMessage()
    ]);
}
