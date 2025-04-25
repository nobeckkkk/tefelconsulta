<?php
$placa = $_GET['placa'] ?? null;
if (!$placa) {
    exit(json_encode(['erro' => 'Informe a placa']));
}
$url = "https://api-xry.xyz/query/api/placanacional.php?query=$placa";

$response = file_get_contents($url);
echo $response ?: json_encode(['erro' => 'Erro na consulta']);
