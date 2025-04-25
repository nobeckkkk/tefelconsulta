<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cpf'])) {
    $cpf = $_GET['cpf'] ?? null;
    if (!$cpf) {
        exit(json_encode(['erro' => 'Informe o CPF']));
    }
    $url = "https://api-xry.xyz/query/api/fotoba.php?cpf=$cpf";
    $response = file_get_contents($url);
    
    // Decodificando a resposta da API
    $data = json_decode($response, true);

    // Verificando se há uma foto disponível na resposta
    $fotoBase64 = $data[0]['foto'] ?? null;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta Foto Bahia</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2d0041; /* Fundo roxo escuro */
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .form-container {
            background-color: #6c1e8c; /* Roxo claro */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 320px;
            margin: 100px auto;
            transform: translateY(-30px);
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards; /* Animação de entrada */
        }

        @keyframes fadeInUp {
            0% {
                transform: translateY(-30px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        input[type="text"], input[type="submit"] {
            padding: 12px;
            width: 85%;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus, input[type="submit"]:hover {
            background-color: #9a00cc; /* Roxo mais claro ao focar ou passar o mouse */
            box-shadow: 0 0 10px rgba(255, 0, 255, 0.5);
        }

        input[type="submit"] {
            background-color: #8000b3; /* Roxo escuro */
            color: white;
            cursor: pointer;
            transform: scale(1);
        }

        input[type="submit"]:active {
            transform: scale(0.98); /* Efeito de clique */
        }

        .result {
            margin-top: 20px;
            background-color: #6c1e8c; /* Roxo claro */
            padding: 20px;
            border-radius: 15px;
            color: white;
            text-align: left;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 80%;
            margin: 20px auto;
            transform: translateY(-30px);
            opacity: 0;
            animation: fadeInUp 1s ease-out forwards; /* Animação de entrada */
        }

        .result h3 {
            margin: 0;
            font-size: 1.6em;
            border-bottom: 2px solid #fff;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .result img {
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .result img:hover {
            transform: scale(1.1); /* Efeito de zoom na imagem */
        }

        .error-message {
            color: #ff0;
            font-size: 1.1em;
            margin-top: 15px;
        }

        .form-container h2 {
            margin: 0;
            font-size: 2em;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
        }

        .form-container label {
            font-size: 1.2em;
            color: white;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>

    <div class="form-container">
        <h2>Consulta Foto Bahia</h2>
        <form action="" method="GET">
            <label>Informe o CPF:</label><br>
            <input type="text" name="cpf" required><br><br>
            <input type="submit" value="Consultar">
        </form>
    </div>

    <?php if (isset($fotoBase64)): ?>
        <div class="result">
            <h3>Resultado:</h3>
            <?php
                if (!empty($fotoBase64)) {
                    echo '<p><strong>Foto do Cidadão:</strong></p>';
                    echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($fotoBase64) . '" alt="Foto do Cidadão" width="200">';
                } else {
                    echo '<p class="error-message">Erro: Foto não encontrada ou CPF inválido.</p>';
                }
            ?>
        </div>
    <?php endif; ?>

</body>
</html>
