<?php
$placa = $_GET['placa'] ?? '';
$url = "https://api-xry.xyz/query/api/placanacional.php?query=" . urlencode($placa);
$response = file_get_contents($url);
echo "<pre>$response</pre>";