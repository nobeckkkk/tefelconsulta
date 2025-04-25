<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta CPF - Foto Bahia</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0e0e1a;
            color: #ffffff;
            padding: 40px;
            max-width: 800px;
            margin: auto;
        }
        h2 {
            color: #d400ff;
            margin-bottom: 20px;
        }
        input, button {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
        }
        input {
            width: 250px;
        }
        button {
            background-color: #d400ff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #a000cc;
        }
        form {
            margin-bottom: 30px;
        }
        .resultado {
            background-color: #1a1a2e;
            border: 1px solid #d400ff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px #d400ff55;
        }
        .campo {
            margin-bottom: 10px;
        }
        .campo strong {
            color: #d400ff;
        }
        img {
            max-width: 300px;
            margin-top: 20px;
            border-radius: 8px;
            border: 2px solid #d400ff;
            box-shadow: 0 0 10px #d400ff;
        }
    </style>
</head>
<body>

    <h2>Consulta de Foto por CPF (Bahia)</h2>
    <form method="GET" action="">
        <input type="text" name="cpf" placeholder="Digite o CPF" required>
        <button type="submit">Consultar</button>
    </form>

    <?php
    function consulta_api($url) {
        $res = @file_get_contents($url);
        return json_decode($res, true);
    }

    if (isset($_GET['cpf']) && !empty($_GET['cpf'])) {
        $cpf = preg_replace('/\D/', '', $_GET['cpf']);
        $url = "https://api-xry.xyz/query/api/fotoba.php?cpf=$cpf";
        $res = consulta_api($url);

        echo "<div class='resultado'><h3>Resultado:</h3>";

        if (is_array($res) && isset($res[0])) {
            $dados = $res[0];

            foreach ($dados as $chave => $valor) {
                if ($chave === 'foto' || empty($valor)) continue;
            
                // Verifica se o valor é um array e transforma em string legível
                if (is_array($valor)) {
                    $valor = implode(', ', $valor);
                }
            
                echo "<div class='campo'><strong>" . ucfirst($chave) . ":</strong> " . htmlspecialchars((string)$valor) . "</div>";
            }
            
            if (!empty($dados['foto'])) {
                $foto = $dados['foto'];
                echo "<div><strong>Foto:</strong><br><img src='data:image/jpeg;base64,{$foto}' alt='Foto do cidadão'></div>";
            }
        } else {
            echo "<p>Erro ao consultar CPF ou dados não encontrados.</p>";
        }

        echo "</div>";
    }
    ?>

</body>
</html>
