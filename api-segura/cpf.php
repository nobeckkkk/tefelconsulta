<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cpf'])) {
    $cpf = $_GET['cpf'] ?? '';
    if (!empty($cpf)) {
        // URL da API para consulta do CPF
        $url = "URL_DA_API_DE_CPF?cpf=" . urlencode($cpf);
        $response = file_get_contents($url);
        $resultado = json_decode($response, true);

        // Verificando se os dados foram encontrados
        if (isset($resultado['dados'])) {
            $dados = $resultado['dados'];
            $endereco = $resultado['Endereco'] ?? [];
        } else {
            $dados = null;
            $endereco = null;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta CPF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #000000; /* Fundo preto */
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .form-container {
            background-color: #6c1e8c; /* Roxo claro */
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
            background-color: #8000b3; /* Roxo escuro */
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #9a00cc; /* Roxo mais claro ao passar o mouse */
        }
        .result {
            margin-top: 20px;
            background-color: #6c1e8c; /* Roxo claro */
            padding: 20px;
            border-radius: 10px;
            color: white;
            text-align: left;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 80%;
            margin: 20px auto;
        }
        .result h3 {
            margin: 0;
            font-size: 1.5em;
            border-bottom: 2px solid #fff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .result p {
            margin: 10px 0;
            font-size: 1.1em;
        }
        .result p strong {
            color: #ff0;
        }
        .section-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #ff0;
            margin-top: 20px;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Consulta CPF</h2>
        <form action="consulta_cpf.php" method="GET">
            <input type="text" name="cpf" placeholder="Digite o CPF" required>
            <input type="submit" value="Consultar">
        </form>
    </div>

    <?php if (isset($dados)): ?>
        <div class="result">
            <h3>Resultado da Consulta:</h3>
            <p><strong>Nome:</strong> <?= htmlspecialchars($dados['Nome']) ?></p>
            <p><strong>Nome Social:</strong> <?= htmlspecialchars($dados['Nome Social'] ?? 'Não disponível') ?></p>
            <p><strong>Mãe:</strong> <?= htmlspecialchars($dados['Mae']) ?></p>
            <p><strong>Pai:</strong> <?= htmlspecialchars($dados['Pai']) ?></p>
            <p><strong>Cor:</strong> <?= htmlspecialchars($dados['Cor']) ?></p>
            <p><strong>Gênero:</strong> <?= htmlspecialchars($dados['Genero']) ?></p>
            <p><strong>Nascimento:</strong> <?= htmlspecialchars($dados['Nascimento']) ?></p>
            <p><strong>Tipo Sanguíneo:</strong> <?= htmlspecialchars($dados['Tipo Sanguineo'] ?? 'Não disponível') ?></p>
            <p><strong>Nacionalidade:</strong> <?= htmlspecialchars($dados['Nacionalidade']) ?></p>
            <p><strong>Município de Nascimento:</strong> <?= htmlspecialchars($dados['Municipio de Nascimento']) ?></p>
            <p><strong>Óbito:</strong> <?= htmlspecialchars($dados['Obito']) ?></p>

            <div class="section-title">Endereço:</div>
            <p><strong>Tipo de Logradouro:</strong> <?= htmlspecialchars($endereco['Tipo de Logadouro'] ?? 'Não disponível') ?></p>
            <p><strong>Logradouro:</strong> <?= htmlspecialchars($endereco['Logadouro'] ?? 'Não disponível') ?></p>
            <p><strong>Número:</strong> <?= htmlspecialchars($endereco['Numero'] ?? 'Não disponível') ?></p>
            <p><strong>Complemento:</strong> <?= htmlspecialchars($endereco['Complemento'] ?? 'Não disponível') ?></p>
            <p><strong>Cep:</strong> <?= htmlspecialchars($endereco['Cep'] ?? 'Não disponível') ?></p>
            <p><strong>Bairro:</strong> <?= htmlspecialchars($endereco['Bairro'] ?? 'Não disponível') ?></p>
            <p><strong>Cidade:</strong> <?= htmlspecialchars($endereco['Cidade'] ?? 'Não disponível') ?></p>
            <p><strong>País:</strong> <?= htmlspecialchars($endereco['Pais'] ?? 'Não disponível') ?></p>
        </div>
    <?php endif; ?>

</body>
</html>

