<?php
$paginaAtual = basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '');
$itensMenu = [
  [
    'rotulo' => 'Home',
    'href' => 'index.php',
    'rotas' => ['index.php'],
  ],
  [
    'rotulo' => 'Clientes',
    'href' => 'clientes.php',
    'rotas' => ['clientes.php'],
  ],
  [
    'rotulo' => 'Serviços',
    'href' => 'servicos.php',
    'rotas' => ['servicos.php'],
  ],
  [
    'rotulo' => 'Profissionais',
    'href' => 'profissionais.php',
    'rotas' => ['profissionais.php'],
  ],
  [
    'rotulo' => 'Agendamentos',
    'href' => 'agendamento.php',
    'rotas' => ['agendamento.php', 'agendamentos.php'],
  ],
];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Salão</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPrincipal" aria-controls="navbarPrincipal" aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarPrincipal">
      <ul class="navbar-nav ms-auto">
        <?php foreach ($itensMenu as $item): ?>
          <?php $ativo = in_array($paginaAtual, $item['rotas'], true); ?>
          <li class="nav-item">
            <a class="nav-link<?php echo $ativo ? ' active' : ''; ?>" <?php echo $ativo ? 'aria-current="page"' : ''; ?> href="<?php echo $item['href']; ?>">
              <?php echo $item['rotulo']; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</nav>