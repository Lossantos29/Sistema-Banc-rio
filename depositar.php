<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'banco_digital');

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = floatval($_POST['valor']);
    $email = $_SESSION['user_email'];

    $sql = "UPDATE usuarios SET saldo = saldo + ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ds", $valor, $email);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Depósito de $valor realizado com sucesso!";
        header("Location: home.php");
        exit();
    } else {
        echo "Erro ao processar depósito.";
    }
}
// Registra a transação
$sqlTransacao = "INSERT INTO transacoes (usuario_id, tipo_transacao, valor, detalhes) VALUES (?, 'deposito', ?, 'Depósito realizado')";
$stmtTransacao = $conn->prepare($sqlTransacao);
$stmtTransacao->bind_param("id", $user['id'], $valor);
$stmtTransacao->execute();

?>

<!DOCTYPE html>
<html lang="pt" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISPPUDIG - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
        }
        .sidebar {
            background-color: #ff6900;
            color: white;
            width: 250px;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
            position: fixed;
        }
        .sidebar h2 {
            margin: 0 0 20px;
            font-size: 1.5rem;
        }
        .sidebar button {
            display: block;
            width: 100%;
            background-color: transparent;
            color: white;
            border: none;
            text-align: left;
            padding: 10px 0;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .sidebar button:hover {
            background-color: #e05a00;
        }
        .main {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }
        .header {
            background-color: #ff6900;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
        }
        .content {
            text-align: center;
        }
        .codigo {
            background-color: #e8f0fe;
            color: #174ea6;
            padding: 15px;
            border-radius: 8px;
            font-size: 1rem;
            margin-top: 20px;
            text-align: center;
        }
        .hidden {
            display: none;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: calc(100% - 250px);
            margin-left: 250px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
        }
        form input, form button {
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        form button {
            background-color: #ff6900;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #e05a00;
        }
    </style>
    <script>
        function showPage(pageId) {
            const pages = document.querySelectorAll('.page');
            pages.forEach(page => page.classList.add('hidden'));
            document.getElementById(pageId).classList.remove('hidden');
        }
    </script>
</head>
<body>
<div class="sidebar">
    <h2>Menu</h2>
    <button onclick="window.location.href='depositar.php'">Fazer Deposito</button>
    <button onclick="window.location.href='levantar.php'">Levantar</button>
    <button onclick="window.location.href='home.php'">Consultar Saldo</button>
    <button onclick="window.location.href='transferir.php'">Transferir</button>
    <button onclick="window.location.href='historico.php'">Histórico</button>
    <button onclick="window.location.href='index.php'">Sair</button>
</div>
<div class="main">
    <header class="header">
        ISPPUDIG - Bem-vindo
    </header>
<h1>Fazer Depósito</h1>
<form method="POST">
    <input type="number" step="0.01" name="valor" placeholder="Valor a depositar" required>
    <button type="submit">Depositar</button>
</form>
<a href="home.php">Voltar</a>
</body>
</html>

