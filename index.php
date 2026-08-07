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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home - Salão</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
 <style> 
body { font-family: Arial, 
sans-serif; 
text-align: center; 
background-color: #f9f9f9; } 
.status { display: inline-block; 
padding: 15px 30px; 
background-color: #e0f2f1; 
color: #00796b; 
border: 1px solid #b2dfdb; 
border-radius: 5px; font-size: 20px; } 
</style> 
</head> 
<body> 
<?php require_once __DIR__ . '/menu.php'; ?>
<div class="container mt-5">
<div class="status"> <?php echo $mensagem ?? "Mensagem indisponivel."; ?> </div> 
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html> 
