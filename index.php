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
margin: 0;
padding: 0;
height: 100vh;
overflow: hidden;
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
.video-background {
position: fixed;
inset: 0;
overflow: hidden;
z-index: -1;
}
.video-background video {
width: 100%;
height: 100%;
display: block;
object-fit: cover;
}
.content {
position: relative;
z-index: 1;
width: 100vw;
height: 100vh;
display: flex;
align-items: center;
justify-content: center;
}
@media (min-width: 1200px) {
body {
display: flex;
flex-direction: column;
}
.content {
flex: 1;
}
}
</style> 
</head> 
<body> 
<?php require __DIR__ . '/menu.php'; ?>

<div class="video-background">
  <video autoplay muted loop>
    <source src="studio2.mp4" type="video/mp4">
    Seu navegador não suporta a tag de vídeo.
  </video>
</div>

<div class="container mt-5 text-center content">


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html> 
