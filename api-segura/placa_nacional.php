<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'] ?? '';
    if (!empty($placa)) {
        $url = "https://api-xry.xyz/query/api/placanacional.php?query=" . urlencode($placa);
        $response = file_get_contents($url);
        $resultado = json_decode($response, true);
        
        // Verificar se a resposta contém a chave "data"
        if (isset($resultado['data'])) {
            $data = $resultado['data'];
            
            // Acessar as informações da placa
            $placa = $data['Placa/UF'] ?? 'Não disponível';
            $chassi = $data['Chassi'] ?? 'Não disponível';
            $proprietario = $data['Proprietário'] ?? 'Não disponível';
            $endereco = $data['Endereço'] ?? 'Não disponível';
            $complemento = $data['Complemento'] ?? 'Não disponível';
            $municipio = $data['Município'] ?? 'Não disponível';
            $marca = $data['Marca'] ?? 'Não disponível';
            $ano = $data['Ano Modelo'] ?? 'Não disponível';
            $cor = $data['Cor'] ?? 'Não disponível';
        } else {
            $placa = $chassi = $proprietario = $endereco = $complemento = $municipio = $marca = $ano = $cor = 'Não disponível';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta Placa Nacional</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2d0041;
            color: white;
            text-align: center;
        }
        .form-container {
            background-color: #4b0082;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 300px;
            margin: 100px auto;
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            width: 80%;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #6c1e8c;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #8000b3;
        }
        .result {
            margin-top: 20px;
            background-color: #6c1e8c;
            padding: 15px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Consulta Placa Nacional</h2>
        <form method="post">
            <label>Informe a Placa:</label><br>
            <input type="text" name="placa" required><br><br>
            <input type="submit" value="Consultar">
        </form>

        <?php if (isset($resultado)): ?>
            <div class="result">
                <h3>Resultado da Consulta:</h3>
                <p><strong>Placa:</strong> <?= htmlspecialchars($placa) ?></p>
                <p><strong>Chassi:</strong> <?= htmlspecialchars($chassi) ?></p>
                <p><strong>Proprietário:</strong> <?= htmlspecialchars($proprietario) ?></p>
                <p><strong>Endereço:</strong> <?= htmlspecialchars($endereco) ?></p>
                <p><strong>Complemento:</strong> <?= htmlspecialchars($complemento) ?></p>
                <p><strong>Município:</strong> <?= htmlspecialchars($municipio) ?></p>
                <p><strong>Marca:</strong> <?= htmlspecialchars($marca) ?></p>
                <p><strong>Ano:</strong> <?= htmlspecialchars($ano) ?></p>
                <p><strong>Cor:</strong> <?= htmlspecialchars($cor) ?></p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
