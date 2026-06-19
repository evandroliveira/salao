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

$idBruto = $_POST['id_servico'] ?? $_POST['id'] ?? '';
$id = 0;

if ($idBruto !== '') {
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
}

$nome = trim($_POST['nome_servico'] ?? $_POST['nome'] ?? '');
$precoBruto = trim((string) ($_POST['preco'] ?? ''));
$duracaoBruto = $_POST['duracao_minutos'] ?? $_POST['duracao'] ?? null;

if ($nome === '') {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'O nome do serviço e obrigatorio.'
    ]);
    exit;
}

$precoNormalizado = str_replace(',', '.', $precoBruto);

if ($precoNormalizado === '' || !is_numeric($precoNormalizado) || (float) $precoNormalizado < 0) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe um preço valido.'
    ]);
    exit;
}

$duracao = filter_var($duracaoBruto, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if ($duracao === false) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe uma duração valida em minutos.'
    ]);
    exit;
}

$preco = number_format((float) $precoNormalizado, 2, '.', '');

try {
    if ($id > 0) {
        $stmt = $conexao->prepare('UPDATE servicos SET nome_servico = :nome_servico, preco = :preco, duracao_minutos = :duracao_minutos WHERE id_servico = :id_servico');
        $stmt->execute([
            ':nome_servico' => $nome,
            ':preco' => $preco,
            ':duracao_minutos' => $duracao,
            ':id_servico' => $id,
        ]);

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Serviço atualizado com sucesso.'
        ]);
        exit;
    }

    $stmt = $conexao->prepare('INSERT INTO servicos (nome_servico, preco, duracao_minutos) VALUES (:nome_servico, :preco, :duracao_minutos)');
    $stmt->execute([
        ':nome_servico' => $nome,
        ':preco' => $preco,
        ':duracao_minutos' => $duracao,
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Serviço cadastrado com sucesso.',
        'id_servico' => (int) $conexao->lastInsertId()
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao salvar serviço: ' . $e->getMessage()
    ]);
}