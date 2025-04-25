<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de CPF</title>
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
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Consultar CPF</h2>
        <form action="cpf.php" method="GET">
            <label for="cpf">Informe o CPF:</label><br>
            <input type="text" name="cpf" id="cpf" placeholder="Ex: 35002284884" required><br>
            <input type="submit" value="Consultar">
        </form>
    </div>

</body>
</html>
