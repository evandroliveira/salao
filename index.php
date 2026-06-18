<?php
// Define um valor padrao para evitar aviso de variavel indefinida.
$mensagem = "Configuracao nao carregada.";

// Usa caminho absoluto para funcionar em qualquer diretório atual de execucao.
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html> 
<html lang="pt-BR"> 
<head> 
<meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Home - Salão</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style> 
body { 
background-color: #f9f9f9; 
} 
.status { 
display: inline-block; 
padding: 15px 30px; 
background-color: #e0f2f1; 
color: #00796b; 
border: 1px solid #b2dfdb; 
border-radius: 5px; 
font-size: 20px; 
} 
</style> 
</head> 
<body> 
    
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Salão</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="clientes.php">Clientes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Serviços</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5 text-center">
<div class="status"> <?php echo $mensagem ?? "Mensagem indisponivel."; ?> </div> 
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html> 
