<?php
// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'] ?? null;
    $placa = $_POST['placa'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $nome = $_POST['nome'] ?? null;

    // Definindo as URLs das APIs
    $urlFotoBahia = "https://api-xry.xyz/query/api/fotoba.php?cpf=$cpf";
    $urlPlacaNacional = "https://api-xry.xyz/query/api/placanacional.php?query=$placa";
    $urlPlacaSenatran = "https://api-xry.xyz/query/api/placa_senatran.php?placa=$placa";
    $urlCPF = "https://meulocalhostapis.shop/apis/sisregi/cpf.php?cpf=$cpf";
    $urlNome = "https://meulocalhostapis.shop/apis/nome.php?token=@detencao&nome=" . urlencode($nome);
    $urlTelefone = "https://meulocalhostapis.shop/apis/telefone.php?token=@detencao&telefone=$telefone";

    // Função para realizar a consulta à API e retornar o JSON
    function consultaAPI($url) {
        $response = file_get_contents($url);
        if ($response === FALSE) {
            return "Erro na consulta da API.";
        }
        return json_decode($response, true);
    }

    // Consultar as APIs
    if ($cpf) {
        $fotoBahia = consultaAPI($urlFotoBahia);
        $consultaCPF = consultaAPI($urlCPF);
    }
    if ($placa) {
        $placaNacional = consultaAPI($urlPlacaNacional);
        $placaSenatran = consultaAPI($urlPlacaSenatran);
    }
    if ($telefone) {
        $consultaTelefone = consultaAPI($urlTelefone);
    }
    if ($nome) {
        $consultaNome = consultaAPI($urlNome);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2d0041;
            color: white;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .form-container {
            background-color: #4b0082;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 80%;
            max-width: 800px;
        }
        .form-container input {
            margin: 10px;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-container button {
            background-color: #6c1e8c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #8000b3;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #3c007d;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Faça uma Consulta</h2>
        <form method="POST">
            <input type="text" name="cpf" placeholder="Informe o CPF" />
            <input type="text" name="placa" placeholder="Informe a Placa" />
            <input type="text" name="telefone" placeholder="Informe o Telefone" />
            <input type="text" name="nome" placeholder="Informe o Nome" />
            <button type="submit">Consultar</button>
        </form>

        <?php if (isset($fotoBahia)) { ?>
            <div class="result">
                <h3>Resultado Foto Bahia:</h3>
                <pre><?php print_r($fotoBahia); ?></pre>
            </div>
        <?php } ?>
        <?php if (isset($placaNacional)) { ?>
            <div class="result">
                <h3>Resultado Placa Nacional:</h3>
                <pre><?php print_r($placaNacional); ?></pre>
            </div>
        <?php } ?>
        <?php if (isset($placaSenatran)) { ?>
            <div class="result">
                <h3>Resultado Placa Senatran:</h3>
                <pre><?php print_r($placaSenatran); ?></pre>
            </div>
        <?php } ?>
        <?php if (isset($consultaCPF)) { ?>
            <div class="result">
                <h3>Resultado CPF:</h3>
                <pre><?php print_r($consultaCPF); ?></pre>
            </div>
        <?php } ?>
        <?php if (isset($consultaNome)) { ?>
            <div class="result">
                <h3>Resultado Nome:</h3>
                <pre><?php print_r($consultaNome); ?></pre>
            </div>
        <?php } ?>
        <?php if (isset($consultaTelefone)) { ?>
            <div class="result">
                <h3>Resultado Telefone:</h3>
                <pre><?php print_r($consultaTelefone); ?></pre>
            </div>
        <?php } ?>
    </div>

</body>
</html>
