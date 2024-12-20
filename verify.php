<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'banco_digital');

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$documentNumber = htmlspecialchars($_POST['document_number']);

// Verifica se o Nº de BI ou Passaporte existe no banco de dados
$sql = "SELECT * FROM usuarios WHERE bi_passaporte = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $documentNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Redireciona para a página de login
    $user = $result->fetch_assoc();
    $_SESSION['user_name'] = $user['nome_completo'];
    $_SESSION['user_email'] = $user['email'];
    header("Location: login.php");
} else {
    // Redireciona para a página de cadastro
    $_SESSION['document_number'] = $documentNumber;
    header("Location: register.php");
}


