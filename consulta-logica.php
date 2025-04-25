<?php
$placa = $_GET['placa'] ?? null;
$cpf = $_GET['cpf'] ?? null;
$data = [];

if ($placa) {
    $data = [
        'placa' => strtoupper($placa),
        'modelo' => 'Civic EXL',
        'ano' => 2020,
        'chassi' => '9BWZZZ377VT004251'
    ];
} elseif ($cpf) {
    $data = [
        'cpf' => $cpf,
        'nome' => 'João da Silva',
        'nascimento' => '1990-01-01',
        'sexo' => 'Masculino'
    ];
} else {
    echo json_encode(['status' => 'error', 'message' => 'Informe placa ou CPF.']);
    exit;
}

$log = date('Y-m-d H:i:s') . " | IP: {$_SERVER['REMOTE_ADDR']} | Consulta: " . ($placa ?: $cpf) . PHP_EOL;
file_put_contents(__DIR__ . '/logs/consultas.log', $log, FILE_APPEND);

echo json_encode(['status' => 'success', 'data' => $data]);