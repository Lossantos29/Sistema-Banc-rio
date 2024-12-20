<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'banco_digital');

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$email = $_SESSION['user_email'];
$password = htmlspecialchars($_POST['password']);

// Consulta para buscar o usuário pelo e-mail
$sql = "SELECT senha FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verifica se a senha está correta
    if (password_verify($password, $user['senha'])) {
        header("Location: home.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Senha incorreta. Tente novamente.";
        header("Location: login.php");
        exit();
    }
} else {
    echo "Erro: Usuário não encontrado.";
}


