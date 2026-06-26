<?php
// id_agendamento	id_cliente	id_profissional	id_servico	data_hora	status
require_once 'config.php';

// Adicionar agendamento com um botão modal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_profissional = $_POST['id_profissional'];
    $id_servico = $_POST['id_servico'];
    $data_hora = $_POST['data_hora'];
    $status = $_POST['status'];

    $sql = "INSERT INTO agendamentos (id_cliente, id_profissional, id_servico, data_hora, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id_cliente, $id_profissional, $id_servico, $data_hora, $status]);

    header("Location: agendamento.php");
    exit;
}
// Editar agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $id_cliente = $_POST['id_cliente'];
    $id_profissional = $_POST['id_profissional'];
    $id_servico = $_POST['id_servico'];
    $data_hora = $_POST['data_hora'];
    $status = $_POST['status'];

    $sql = "UPDATE agendamentos SET id_cliente = ?, id_profissional = ?, id_servico = ?, data_hora = ?, status = ? WHERE id_agendamento = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id_cliente, $id_profissional, $id_servico, $data_hora, $status, $id]);

    header("Location: agendamento.php");
    exit;
}

// Excluir agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM agendamentos WHERE id_agendamento = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id]);

    header("Location: agendamento.php");
    exit;
}

// Buscar agendamentos com dados dos relacionamentos
$sql = "SELECT 
    a.id_agendamento,
    c.nome as cliente_nome,
    p.nome as profissional_nome,
    s.nome_servico as servico_nome,
    a.data_hora,
    a.status
FROM agendamentos a
INNER JOIN clientes c ON a.id_cliente = c.id_cliente
INNER JOIN profissionais p ON a.id_profissional = p.id
INNER JOIN servicos s ON a.id_servico = s.id_servico
ORDER BY a.data_hora DESC";
$result = $conexao->query($sql);
$agendamentos = $result->fetchAll(PDO::FETCH_ASSOC);

// Buscar clientes, profissionais e serviços para o formulário
$clientes = $conexao->query("SELECT id_cliente, nome FROM clientes ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$profissionais = $conexao->query("SELECT id, nome FROM profissionais ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$servicos = $conexao->query("SELECT id_servico, nome_servico FROM servicos ORDER BY nome_servico")->fetchAll(PDO::FETCH_ASSOC);

// Buscar agendamento para edição se solicitado
$agendamento_edicao = null;
if (isset($_GET['editar'])) {
    $sql = "SELECT * FROM agendamentos WHERE id_agendamento = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$_GET['editar']]);
    $agendamento_edicao = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-fluid {
            max-width: 1200px;
            margin-top: 30px;
        }
        .table-responsive {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <?php require __DIR__ . '/menu.php'; ?>

    <div class="container-fluid">
        <h1 class="mb-4">Agendamentos</h1>

        <!-- Botão Modal para Adicionar -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalAgendamento">
            <i class="bi bi-plus"></i> Novo Agendamento
        </button>

        <!-- Modal -->
        <div class="modal fade" id="modalAgendamento" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo isset($agendamento_edicao) ? 'Editar Agendamento' : 'Novo Agendamento'; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <?php if ($agendamento_edicao): ?>
                                <input type="hidden" name="id" value="<?php echo $agendamento_edicao['id_agendamento']; ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="id_cliente" class="form-label">Cliente</label>
                                <select class="form-select" id="id_cliente" name="id_cliente" required>
                                    <option value="">Selecione um cliente</option>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?php echo $cliente['id_cliente']; ?>" 
                                            <?php echo ($agendamento_edicao && $agendamento_edicao['id_cliente'] == $cliente['id_cliente']) ? 'selected' : ''; ?>>
                                            <?php echo $cliente['nome']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="id_profissional" class="form-label">Profissional</label>
                                <select class="form-select" id="id_profissional" name="id_profissional" required>
                                    <option value="">Selecione um profissional</option>
                                    <?php foreach ($profissionais as $profissional): ?>
                                        <option value="<?php echo $profissional['id']; ?>"
                                            <?php echo ($agendamento_edicao && $agendamento_edicao['id_profissional'] == $profissional['id']) ? 'selected' : ''; ?>>
                                            <?php echo $profissional['nome']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="id_servico" class="form-label">Serviço</label>
                                <select class="form-select" id="id_servico" name="id_servico" required>
                                    <option value="">Selecione um serviço</option>
                                    <?php foreach ($servicos as $servico): ?>
                                        <option value="<?php echo $servico['id_servico']; ?>"
                                            <?php echo ($agendamento_edicao && $agendamento_edicao['id_servico'] == $servico['id_servico']) ? 'selected' : ''; ?>>
                                            <?php echo $servico['nome_servico']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="data_hora" class="form-label">Data e Hora</label>
                                <input type="datetime-local" class="form-control" id="data_hora" name="data_hora" 
                                    value="<?php echo $agendamento_edicao ? $agendamento_edicao['data_hora'] : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pendente" <?php echo ($agendamento_edicao && $agendamento_edicao['status'] == 'pendente') ? 'selected' : ''; ?>>Pendente</option>
                                    <option value="confirmado" <?php echo ($agendamento_edicao && $agendamento_edicao['status'] == 'confirmado') ? 'selected' : ''; ?>>Confirmado</option>
                                    <option value="concluído" <?php echo ($agendamento_edicao && $agendamento_edicao['status'] == 'concluído') ? 'selected' : ''; ?>>Concluído</option>
                                    <option value="cancelado" <?php echo ($agendamento_edicao && $agendamento_edicao['status'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="<?php echo isset($agendamento_edicao) ? 'editar' : 'adicionar'; ?>" class="btn btn-primary">
                                <?php echo isset($agendamento_edicao) ? 'Atualizar' : 'Adicionar'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabela de Agendamentos -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Cliente</th>
                        <th>Profissional</th>
                        <th>Serviço</th>
                        <th>Data e Hora</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($agendamentos) > 0): ?>
                        <?php foreach ($agendamentos as $agendamento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($agendamento['cliente_nome']); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['profissional_nome']); ?></td>
                                <td><?php echo htmlspecialchars($agendamento['servico_nome']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($agendamento['data_hora'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $agendamento['status'] == 'confirmado' ? 'success' : 
                                             ($agendamento['status'] == 'pendente' ? 'warning' : 
                                             ($agendamento['status'] == 'concluído' ? 'info' : 'danger')); 
                                    ?>">
                                        <?php echo ucfirst($agendamento['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="agendamento.php?editar=<?php echo $agendamento['id_agendamento']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Deseja realmente excluir?');">
                                        <input type="hidden" name="id" value="<?php echo $agendamento['id_agendamento']; ?>">
                                        <button type="submit" name="excluir" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Nenhum agendamento encontrado</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('modalAgendamento');
            if (!modalElement) {
                return;
            }

            <?php if ($agendamento_edicao): ?>
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            modalElement.addEventListener('hidden.bs.modal', function () {
                window.location.href = 'agendamento.php';
            }, { once: true });
            <?php endif; ?>
        });
    </script>
</body>
</html>
