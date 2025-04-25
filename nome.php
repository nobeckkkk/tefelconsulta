<?php
$nome = $_GET['nome'] ?? null;
if (!$nome) {
    exit(json_encode(['erro' => 'Informe o nome']));
}
$token = '@detencao';
$url = "https://meulocalhostapis.shop/apis/nome.php?token=$token&nome=" . urlencode($nome);

$response = file_get_contents($url);
echo $response ?: json_encode(['erro' => 'Erro na consulta']);
