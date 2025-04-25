<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "teufel_consultas");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o usuário é admin
$stmt = $conn->prepare("SELECT nivel FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $_SESSION['usuario']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$is_admin = $usuario['nivel'] === 'admin';
$stmt->close();

// Processa a criação de novo login
if ($_SERVER["REQUEST_METHOD"] == "POST" && $is_admin && isset($_POST['criar_usuario'])) {
    $novo_usuario = trim($_POST['novo_usuario']);
    $nova_senha = trim($_POST['nova_senha']);
    $nivel = $_POST['nivel'];

    if (empty($novo_usuario) || empty($nova_senha)) {
        $erro = "Preencha todos os campos!";
    } elseif (!in_array($nivel, ['admin', 'usuario'])) {
        $erro = "Nível de acesso inválido.";
    } else {
        // Verifica se o usuário já existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $novo_usuario);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $erro = "Usuário já existe.";
        } else {
            // Cria o novo usuário
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (usuario, senha, nivel) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $novo_usuario, $senha_hash, $nivel);
            if ($stmt->execute()) {
                $sucesso = "Usuário criado com sucesso!";
            } else {
                $erro = "Erro ao criar usuário.";
            }
        }
        $stmt->close();
    }
}

// Obtém a lista de usuários (para admins)
$usuarios = [];
if ($is_admin) {
    $result = $conn->query("SELECT id, usuario, nivel, criado_em FROM usuarios ORDER BY criado_em DESC");
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Teufel Consultas - Painel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #000;
      color: #fff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      overflow-x: hidden;
    }

    .sidebar {
      width: 250px;
      background: #111;
      border-right: 2px solid #a200ff;
      box-shadow: 0 0 20px #a200ff;
      padding: 20px;
      position: fixed;
      height: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .sidebar h2 {
      color: #a200ff;
      text-shadow: 0 0 10px #a200ff;
      font-size: 24px;
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar a, .sidebar button {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 15px;
      background: #6c1e8c;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-size: 16px;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .sidebar a:hover, .sidebar button:hover {
      background: #8000b3;
      box-shadow: 0 0 15px #a200ff;
      transform: scale(1.05);
    }

    .main-content {
      margin-left: 270px;
      padding: 40px;
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      font-size: 36px;
      text-shadow: 0 0 10px #a200ff;
      color: #a200ff;
      margin-bottom: 40px;
    }

    .menu {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 15px;
      width: 100%;
      max-width: 400px;
    }

    .link-btn {
      padding: 15px 30px;
      width: 100%;
      background: #6c1e8c;
      color: #fff;
      border: none;
      border-radius: 12px;
      text-decoration: none;
      font-size: 18px;
      box-shadow: 0 0 10px rgba(108, 30, 140, 0.6);
      transition: all 0.3s ease;
      text-align: center;
    }

    .link-btn:hover {
      background: #8000b3;
      box-shadow: 0 0 20px rgba(128, 0, 179, 0.8);
      transform: scale(1.05);
    }

    .admin-section {
      margin-top: 40px;
      width: 100%;
      max-width: 600px;
      background: #111;
      padding: 20px;
      border-radius: 12px;
      border: 2px solid #a200ff;
      box-shadow: 0 0 20px #a200ff;
    }

    .admin-section h3 {
      color: #a200ff;
      text-shadow: 0 0 10px #a200ff;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
      text-align: left;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: #fff;
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 10px;
      background: #222;
      border: none;
      border-radius: 5px;
      color: #fff;
    }

    .form-group input::placeholder {
      color: #ddd;
    }

    .btn {
      padding: 12px;
      width: 100%;
      background: transparent;
      color: #fff;
      border: 2px solid #fff;
      border-radius: 5px;
      font-weight: bold;
      box-shadow: 0 0 10px #fff;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      background: #fff;
      color: #a200ff;
      box-shadow: 0 0 15px #fff;
    }

    .alerta {
      background: #ff0033;
      color: #fff;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      font-weight: bold;
      animation: piscaAlerta 0.3s ease-in-out infinite alternate;
    }

    .sucesso {
      background: #00cc00;
      color: #fff;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      font-weight: bold;
    }

    @keyframes piscaAlerta {
      from { box-shadow: 0 0 10px #ff0033; }
      to { box-shadow: 0 0 20px #ff3366; }
    }

    .usuarios-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .usuarios-table th, .usuarios-table td {
      padding: 10px;
      border: 1px solid #a200ff;
      text-align: left;
    }

    .usuarios-table th {
      background: #6c1e8c;
      color: #fff;
    }

    .usuarios-table td {
      background: #222;
    }
  </style>
</head>
<body>
  <!-- Menu Lateral -->
  <div class="sidebar">
    <h2>Teufel Consul</h2>
    <a href="painel.php"><i class="fas fa-home"></i> Início</a>
    <?php if ($is_admin): ?>
      <button onclick="toggleAdminSection()"><i class="fas fa-user-plus"></i> Criar Login</button>
      <button onclick="toggleUsuariosSection()"><i class="fas fa-users"></i> Ver Usuários</button>
    <?php endif; ?>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
  </div>

  <!-- Conteúdo Principal -->
  <div class="main-content">
    <h1>Teufel Consultas</h1>
    <div class="menu">
      <a class="link-btn" href="cpf.php">Consulta CPF (SISREGIII)</a>
      <a class="link-btn" href="nome.php">Consulta Nome</a>
      <a class="link-btn" href="telefone.php">Consulta Telefone</a>
      <a class="link-btn" href="foto_ba.php">Foto Bahia</a>
      <a class="link-btn" href="fotope.php">Foto Pernambuco</a>
      <a class="link-btn" href="placa_nacional.php">Placa Nacional</a>
      <a class="link-btn" href="placa_senatran.php">Placa Senatran</a>
    </div>

    <!-- Seção de Administração (oculta por padrão) -->
    <?php if ($is_admin): ?>
      <div class="admin-section" id="adminSection" style="display: none;">
        <h3>Criar Novo Login</h3>
        <?php if (isset($erro)): ?>
          <div class="alerta"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>
        <?php if (isset($sucesso)): ?>
          <div class="sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
          <div class="form-group">
            <label for="novo_usuario">Usuário</label>
            <input type="text" id="novo_usuario" name="novo_usuario" placeholder="Digite o usuário" required>
          </div>
          <div class="form-group">
            <label for="nova_senha">Senha</label>
            <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite a senha" required>
          </div>
          <div class="form-group">
            <label for="nivel">Nível de Acesso</label>
            <select id="nivel" name="nivel" required>
              <option value="usuario">Usuário</option>
              <option value="admin">Administrador</option>
            </select>
          </div>
          <button type="submit" name="criar_usuario" class="btn">Criar Usuário</button>
        </form>
      </div>

      <!-- Seção de Listagem de Usuários (oculta por padrão) -->
      <div class="admin-section" id="usuariosSection" style="display: none;">
        <h3>Usuários Cadastrados</h3>
        <table class="usuarios-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Usuário</th>
              <th>Nível</th>
              <th>Criado Em</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $u): ?>
              <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['usuario']); ?></td>
                <td><?php echo $u['nivel']; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($u['criado_em'])); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <script>
    function toggleAdminSection() {
      const section = document.getElementById('adminSection');
      section.style.display = section.style.display === 'none' ? 'block' : 'none';
      document.getElementById('usuariosSection').style.display = 'none';
    }

    function toggleUsuariosSection() {
      const section = document.getElementById('usuariosSection');
      section.style.display = section.style.display === 'none' ? 'block' : 'none';
      document.getElementById('adminSection').style.display = 'none';
    }

    // Remove mensagens de erro/sucesso após 3 segundos
    const mensagens = document.querySelectorAll('.alerta, .sucesso');
    mensagens.forEach(msg => {
      setTimeout(() => msg.remove(), 3000);
    });
  </script>
</body>
</html>