<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de CPF - Foto Pernambuco</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000; /* Fundo preto */
            color: #fff; /* Texto branco */
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #222; /* Fundo escuro no container */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #6a1b9a; /* Cor roxa */
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #6a1b9a; /* Cor roxa */
        }

        .input-group input {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #333; /* Fundo escuro no input */
            color: #fff; /* Texto branco */
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 18px;
            background-color: #6a1b9a; /* Cor roxa */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #4a148c; /* Cor roxa escura */
        }

        .result {
            margin-top: 30px;
            padding: 15px;
            background-color: #333; /* Fundo escuro */
            border: 1px solid #444;
            border-radius: 5px;
        }

        .result h3 {
            margin-top: 0;
            color: #6a1b9a; /* Cor roxa */
        }

        .result img {
            max-width: 300px;
            max-height: 400px;
        }

        .redirect-link {
            margin-top: 20px;
            text-align: center;
        }

        .redirect-link a {
            color: #6a1b9a; /* Cor roxa */
            font-size: 16px;
            text-decoration: none;
        }

        .redirect-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Consulta de CPF - Foto Pernambuco</h1>

        <!-- Formulário para consulta de CPF -->
        <form method="GET" action="" class="input-group">
            <label for="cpf">Informe o CPF:</label>
            <input type="text" name="cpf" id="cpf" required>
            <button type="submit" class="btn">Consultar</button>
        </form>

        <?php
        if (isset($_GET['cpf']) && !empty($_GET['cpf'])) {
            $cpf = $_GET['cpf'];
            
            // URL da API de consulta
            $url = "http://localhost/fotope.php?query=$cpf";

            // Faz a requisição à API
            $response = file_get_contents($url);
            $data = json_decode($response, true);  // Decodifica a resposta JSON

            // Verifica se a API retornou sucesso
            if (isset($data['status']) && $data['status'] == "200") {
                // Dados da API
                $nome = htmlspecialchars($data['nome']);
                $nascimento = htmlspecialchars($data['nascimento']);
                $idade = htmlspecialchars($data['idade']);
                $foto_base64 = htmlspecialchars($data['foto']);  // Foto codificada em Base64

                // Exibe os dados na tela
                echo "<div class='result'>";
                echo "<h3>Resultado da Consulta Detalhada</h3>";
                echo "<p><strong>Nome:</strong> $nome</p>";
                echo "<p><strong>Data de Nascimento:</strong> $nascimento</p>";
                echo "<p><strong>Idade:</strong> $idade anos</p>";
                
                // Exibe a foto, se disponível
                if (!empty($foto_base64)) {
                    echo "<h4>Foto Completa:</h4>";
                    echo "<img src='data:image/jpeg;base64,$foto_base64' alt='Foto do CPF'>";
                } else {
                    echo "<p>Foto não disponível.</p>";
                }
                echo "</div>";

                // Link para redirecionamento para página de consulta
                echo "<div class='redirect-link'>";
                echo "<p>Quer consultar mais detalhes? <a href='http://localhost/consultar_foto_pe.php?cpf=$cpf'>Clique aqui para consultar a foto completa de Pernambuco</a></p>";
                echo "</div>";
            } else {
                echo "<p>Erro ao buscar os dados. Detalhes: " . htmlspecialchars($data['message'] ?? 'Desconhecido') . "</p>";
            }
        }
        ?>
    </div>

</body>
</html>
