<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$ips_permitidos = ['127.0.0.1', '::1'];
$ip_usuario = $_SERVER['REMOTE_ADDR'];
if (!in_array($ip_usuario, $ips_permitidos)) {
    echo json_encode(['status' => 'unauthorized', 'message' => 'IP não autorizado']);
    exit;
}

$headers = getallheaders();
$authorization = $headers['Authorization'] ?? '';
$token = str_replace('Bearer ', '', $authorization);
$secretKey = 'minha_chave_secreta123';

try {
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
} catch (Exception $e) {
    echo json_encode(['status' => 'unauthorized', 'message' => 'Token inválido ou expirado']);
    exit;
}

require 'consulta-logica.php';