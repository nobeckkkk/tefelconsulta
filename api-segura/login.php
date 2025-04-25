<?php
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "teufel_consultas");
    if ($conn->connect_error) {
        $erro = "Erro de conexão com o banco de dados.";
    } else {
        // Validação
        if (empty($usuario) || empty($senha)) {
            $erro = "Preencha todos os campos!";
        } else {
            // Verifica as credenciais
            $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE usuario = ?");
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                $erro = "Usuário ou senha incorretos.";
            } else {
                $row = $result->fetch_assoc();
                if (password_verify($senha, $row['senha'])) {
                    $_SESSION['usuario'] = $usuario;
                    header("Location: painel.php");
                    exit();
                } else {
                    $erro = "Usuário ou senha incorretos.";
                }
            }
            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Teufel Consul - Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      background: #000;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .neon-box {
      background: #111;
      padding: 2rem;
      border-radius: 12px;
      border: 2px solid #a200ff;
      box-shadow: 0 0 30px #a200ff;
      width: 320px;
      text-align: center;
      position: relative;
    }

    h1 {
      color: #a200ff;
      margin: 0;
      font-size: 1.8rem;
      text-shadow: 0 0 10px #a200ff;
    }

    .subtitle {
      color: #fff;
      font-weight: bold;
      margin-bottom: 1rem;
      text-shadow: 0 0 5px #fff;
    }

    .input-icon {
      position: relative;
      margin-bottom: 1rem;
    }

    .input-icon input {
      width: 100%;
      padding: 10px 40px 10px 30px;
      background: #222;
      border: none;
      border-radius: 5px;
      color: #fff;
    }

    .input-icon input::placeholder {
      color: #ddd;
    }

    .input-icon i.fas.fa-user,
    .input-icon i.fas.fa-lock {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #fff;
    }

    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #fff;
      font-size: 1rem;
    }

    .btn {
      background: transparent;
      color: #fff;
      border: 2px solid #fff;
      padding: 12px;
      width: 100%;
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
      color: white;
      padding: 10px;
      margin-bottom: 1rem;
      border-radius: 5px;
      font-weight: bold;
      animation: piscaAlerta 0.3s ease-in-out infinite alternate;
    }

    @keyframes piscaAlerta {
      from { box-shadow: 0 0 10px #ff0033; }
      to { box-shadow: 0 0 20px #ff3366; }
    }

    .suporte-links {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 15px;
    }

    .suporte-links a {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: #1d1d1d;
      color: #fff;
      text-decoration: none;
      padding: 10px;
      border: 1px solid #a200ff;
      border-radius: 6px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .suporte-links a:hover {
      background: #a200ff;
      color: #000;
    }
  </style>
</head>
<body>
  <div class="neon-box">
    <h1>TEUFEL CONSUL</h1>
    <p class="subtitle">LOGIN</p>

    <!-- Exibe mensagem de erro, se houver -->
    <?php if (isset($erro)): ?>
      <div class="alerta"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <!-- Formulário de login -->
    <form method="POST" action="">
      <div class="input-icon">
        <i class="fas fa-user"></i>
        <input type="text" placeholder="Usuário" name="usuario" id="userInput" value="<?php echo isset($usuario) ? htmlspecialchars($usuario) : ''; ?>">
      </div>

      <div class="input-icon">
        <i class="fas fa-lock"></i>
        <input type="password" placeholder="Senha" id="senhaInput" name="senha">
        <span class="toggle-password" onclick="toggleSenha()">
          <i class="fas fa-eye" id="toggleIcon"></i>
        </span>
      </div>

      <button type="submit" class="btn">ENTRAR</button>
    </form>

    <div class="suporte-links">
      <a href="https://t.me/detencao" target="_blank">
        <i class="fab fa-telegram"></i> Suporte 1
      </a>
      <a href="https://t.me/brancoesperto" target="_blank">
        <i class="fab fa-telegram"></i> Suporte 2
      </a>
    </div>
  </div>

  <script>
    function toggleSenha() {
      const input = document.getElementById('senhaInput');
      const icon = document.getElementById('toggleIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }

    // Exibe mensagem de erro por 3 segundos
    const alerta = document.querySelector('.alerta');
    if (alerta) {
      setTimeout(() => {
        alerta.remove();
      }, 3000);
    }
  </script>
</body>
</html>