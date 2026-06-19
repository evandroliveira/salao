<?php
session_start();
include 'config.php';

$sql = 'SELECT id_servico, nome_servico, preco, duracao_minutos FROM servicos ORDER BY nome_servico';
$result = $conexao->query($sql);
$servicos = $result ? $result->fetchAll() : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços - Salão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Salão</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="clientes.php">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="servicos.php">Serviços</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Serviços</h1>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalServico" onclick="prepararNovoServico()">
                <i class="bi bi-plus-circle"></i> Novo Serviço
            </button>
        </div>

        <?php if (count($servicos) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Duração</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicos as $servico): ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string) $servico['id_servico']); ?></td>
                                <td><?php echo htmlspecialchars($servico['nome_servico']); ?></td>
                                <td>R$ <?php echo htmlspecialchars(number_format((float) $servico['preco'], 2, ',', '.')); ?></td>
                                <td><?php echo htmlspecialchars((string) $servico['duracao_minutos']); ?> min</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#modalServico" onclick="carregarServico(<?php echo (int) $servico['id_servico']; ?>)">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-danger" type="button" onclick="deletarServico(<?php echo (int) $servico['id_servico']; ?>)">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Nenhum serviço cadastrado. <a href="#" class="alert-link" data-bs-toggle="modal" data-bs-target="#modalServico" onclick="prepararNovoServico()">Adicionar serviço</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="modalServico" tabindex="-1" aria-labelledby="modalServicoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalServicoLabel">Novo Serviço</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formServico">
                    <div class="modal-body">
                        <input type="hidden" id="servicoId" name="id_servico">

                        <div class="mb-3">
                            <label for="servicoNome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="servicoNome" name="nome_servico" required>
                        </div>

                        <div class="mb-3">
                            <label for="servicoPreco" class="form-label">Preço *</label>
                            <input type="number" class="form-control" id="servicoPreco" name="preco" min="0" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="servicoDuracao" class="form-label">Duração (minutos) *</label>
                            <input type="number" class="form-control" id="servicoDuracao" name="duracao_minutos" min="1" step="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const modalServicoElement = document.getElementById('modalServico');
        const modalServico = new bootstrap.Modal(modalServicoElement);
        const formServico = document.getElementById('formServico');

        function prepararNovoServico() {
            formServico.reset();
            document.getElementById('servicoId').value = '';
            document.getElementById('modalServicoLabel').textContent = 'Novo Serviço';
        }

        modalServicoElement.addEventListener('hidden.bs.modal', prepararNovoServico);

        function carregarServico(id) {
            fetch('get_servico.php?id_servico=' + encodeURIComponent(id))
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.mensagem || 'Nao foi possivel carregar o serviço.');
                    }
                    return data;
                })
                .then(data => {
                    document.getElementById('servicoId').value = data.id_servico;
                    document.getElementById('servicoNome').value = data.nome_servico;
                    document.getElementById('servicoPreco').value = data.preco;
                    document.getElementById('servicoDuracao').value = data.duracao_minutos;
                    document.getElementById('modalServicoLabel').textContent = 'Editar Serviço';
                })
                .catch(error => {
                    alert(error.message || 'Erro ao carregar serviço.');
                    console.error('Erro:', error);
                });
        }

        formServico.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('salvar_servico.php', {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.mensagem || 'Nao foi possivel salvar o serviço.');
                }
                return data;
            })
            .then(data => {
                if (data.sucesso) {
                    modalServico.hide();
                    location.reload();
                } else {
                    alert('Erro: ' + data.mensagem);
                }
            })
            .catch(error => {
                alert(error.message || 'Erro ao salvar serviço.');
                console.error('Erro:', error);
            });
        });

        function deletarServico(id) {
            if (confirm('Tem certeza que deseja excluir este serviço?')) {
                fetch('deletar_servico.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ id_servico: id })
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.mensagem || 'Nao foi possivel excluir o serviço.');
                    }
                    return data;
                })
                .then(data => {
                    if (data.sucesso) {
                        location.reload();
                    } else {
                        alert('Erro: ' + data.mensagem);
                    }
                })
                .catch(error => {
                    alert(error.message || 'Erro ao excluir serviço.');
                    console.error('Erro:', error);
                });
            }
        }

        prepararNovoServico();
    </script>
</body>
</html>