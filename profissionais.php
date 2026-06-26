<?php
session_start();
include 'config.php';

// Adicionar profissional
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];
    
    $sql = "INSERT INTO profissionais (nome, especialidade) VALUES (?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$nome, $especialidade]);
    
    header("Location: profissionais.php");
    exit;
}

// Editar profissional
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];
    
    $sql = "UPDATE profissionais SET nome = ?, especialidade = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$nome, $especialidade, $id]);
    
    header("Location: profissionais.php");
    exit;
}

// Excluir profissional
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
    $id = $_POST['id'];
    
    $sql = "DELETE FROM profissionais WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id]);
    
    header("Location: profissionais.php");
    exit;
}

// Buscar profissional para edição
$profissionalEdicao = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $sql = "SELECT * FROM profissionais WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([$id]);
    $profissionalEdicao = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar profissionais
$sql = "SELECT * FROM profissionais ORDER BY nome";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profissionais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require __DIR__ . '/menu.php'; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Profissionais</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProfissional">
                + Novo Profissional
            </button>
        </div>

        <!-- Tabela de Profissionais -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Especialidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($profissionais as $profissional): ?>
                    <tr>
                        <td><?php echo $profissional['id']; ?></td>
                        <td><?php echo htmlspecialchars($profissional['nome']); ?></td>
                        <td><?php echo htmlspecialchars($profissional['especialidade']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdicao"
                                onclick="editarProfissional(<?php echo $profissional['id']; ?>, '<?php echo htmlspecialchars($profissional['nome']); ?>', '<?php echo htmlspecialchars($profissional['especialidade']); ?>')">
                                Editar
                            </button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $profissional['id']; ?>">
                                <button type="submit" name="excluir" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Novo Profissional -->
    <div class="modal fade" id="modalProfissional" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cadastrar Novo Profissional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="especialidade" class="form-label">Especialidade</label>
                            <input type="text" class="form-control" id="especialidade" name="especialidade" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="adicionar" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Profissional -->
    <div class="modal fade" id="modalEdicao" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Profissional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editNome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="editNome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEspecialidade" class="form-label">Especialidade</label>
                            <input type="text" class="form-control" id="editEspecialidade" name="especialidade" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editarProfissional(id, nome, especialidade) {
            document.getElementById('editId').value = id;
            document.getElementById('editNome').value = nome;
            document.getElementById('editEspecialidade').value = especialidade;
        }
    </script>
</body>
</html>
