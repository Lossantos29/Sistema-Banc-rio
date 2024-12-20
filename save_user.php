<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'banco_digital');

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$bi_passaporte = htmlspecialchars($_POST['bi_passaporte']);
$nome_completo = htmlspecialchars($_POST['nome_completo']);
$telefone = htmlspecialchars($_POST['telefone']);
$email = htmlspecialchars($_POST['email']);
$morada = htmlspecialchars($_POST['morada']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (bi_passaporte, nome_completo, telefone, email, morada, senha) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $bi_passaporte, $nome_completo, $telefone, $email, $morada, $senha);

if ($stmt->execute()) {
    // Salva o nome do usuário na sessão para exibir na tela de login
    $_SESSION['user_name'] = $nome_completo;
    $_SESSION['user_email'] = $email;
    // Redireciona para a página de login
    header("Location: login.php");
    exit();
} else {
    echo "Erro ao cadastrar: " . $conn->error;
}




