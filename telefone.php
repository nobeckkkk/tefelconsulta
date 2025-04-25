<?php
$telefone = $_GET['telefone'] ?? null;
if (!$telefone) {
    exit(json_encode(['erro' => 'Informe o telefone']));
}
$token = '@detencao';
$url = "https://meulocalhostapis.shop/apis/telefone.php?token=$token&telefone=$telefone";

$response = file_get_contents($url);
echo $response ?: json_encode(['erro' => 'Erro na consulta']);
