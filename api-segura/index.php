<?php
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "seu_usuario", "sua_senha", "teufel_consultas");
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