<?php
session_start();
include 'config.php';

// Buscar clientes
$sql = "SELECT * FROM clientes ORDER BY nome";
$result = $conexao->query($sql);
$clientes = $result ? $result->fetchAll() : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Salão</title>
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
                        <a class="nav-link" href="#">Serviços</a>
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
            <h1>Clientes</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCliente">
                <i class="bi bi-plus-circle"></i> Novo Cliente
            </button>
        </div>

        <?php if (count($clientes) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cliente['id_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefone'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($cliente['email'] ?? ''); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalCliente" onclick="carregarCliente(<?php echo $cliente['id_cliente']; ?>)">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deletarCliente(<?php echo $cliente['id_cliente']; ?>)">
                                        <i class="bi bi-trash"></i> Deletar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Nenhum cliente cadastrado. <a href="#" class="alert-link" data-bs-toggle="modal" data-bs-target="#modalCliente">Adicionar cliente</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Adicionar/Editar Cliente -->
    <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalClienteLabel">Novo Cliente</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formCliente">
                    <div class="modal-body">
                        <input type="hidden" id="clienteId" name="id">
                        
                        <div class="mb-3">
                            <label for="clienteNome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="clienteNome" name="nome" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="clienteTelefone" class="form-label">Telefone</label>
                            <input type="tel" class="form-control" id="clienteTelefone" name="telefone">
                        </div>
                        
                        <div class="mb-3">
                            <label for="clienteEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="clienteEmail" name="email">
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
        const modalCliente = new bootstrap.Modal(document.getElementById('modalCliente'));

        // Limpar formulário ao abrir modal para novo cliente
        document.getElementById('modalCliente').addEventListener('show.bs.modal', function (e) {
            if (!e.relatedTarget || !e.relatedTarget.onclick) {
                document.getElementById('formCliente').reset();
                document.getElementById('clienteId').value = '';
                document.getElementById('modalClienteLabel').textContent = 'Novo Cliente';
            }
        });

        // Carregar dados do cliente para edição
        function carregarCliente(id) {
            fetch('get_cliente.php?id=' + id)
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.mensagem || 'Nao foi possivel carregar o cliente.');
                    }
                    return data;
                })
                .then(data => {
                    document.getElementById('clienteId').value = data.id;
                    document.getElementById('clienteNome').value = data.nome;
                    document.getElementById('clienteTelefone').value = data.telefone || '';
                    document.getElementById('clienteEmail').value = data.email || '';
                    document.getElementById('modalClienteLabel').textContent = 'Editar Cliente';
                })
                .catch(error => {
                    alert(error.message || 'Erro ao carregar cliente.');
                    console.error('Erro:', error);
                });
        }

        // Salvar cliente
        document.getElementById('formCliente').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = document.getElementById('clienteId').value ? 'salvar_cliente.php' : 'salvar_cliente.php';
            
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.mensagem || 'Nao foi possivel salvar o cliente.');
                }
                return data;
            })
            .then(data => {
                if (data.sucesso) {
                    modalCliente.hide();
                    location.reload();
                } else {
                    alert('Erro: ' + data.mensagem);
                }
            })
            .catch(error => {
                alert(error.message || 'Erro ao salvar cliente.');
                console.error('Erro:', error);
            });
        });

        // Deletar cliente
        function deletarCliente(id) {
            if (confirm('Tem certeza que deseja deletar este cliente?')) {
                fetch('deletar_cliente.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + id
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.mensagem || 'Nao foi possivel deletar o cliente.');
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
                    alert(error.message || 'Erro ao deletar cliente.');
                    console.error('Erro:', error);
                });
            }
        }
    </script>
</body>
</html>