<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'banco_digital');

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$email = $_SESSION['user_email'];
$sql = "SELECT saldo FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Saldo</title>
    <style>
        body { text-align: center; padding: 20px; font-family: Arial, sans-serif; }
    </style>
</head>
<body>
<h1>Saldo Atual</h1>
<p>Seu saldo é: <strong><?= number_format($user['saldo'], 2) ?> Kz</strong></p>
<a href="home.php">Voltar</a>
</body>
</html>

