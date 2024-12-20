<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'banco_digital');

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$email = $_SESSION['user_email'];
$sql = "SELECT id FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$sqlTransacoes = "SELECT * FROM transacoes WHERE usuario_id = ? ORDER BY data_hora DESC";
$stmtTransacoes = $conn->prepare($sqlTransacoes);
$stmtTransacoes->bind_param("i", $user['id']);
$stmtTransacoes->execute();
$resultTransacoes = $stmtTransacoes->get_result();
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
             {
                margin-left: 250px;
                padding: 20px;
                flex-grow: 1;
            }
             {
                background-color: #ff6900;
                color: white;
                padding: 20px;
                text-align: center;
                font-size: 1.5rem;
            }
             {
                text-align: center;
            }
             {
                background-color: #e8f0fe;
                color: #174ea6;
                padding: 15px;
                border-radius: 8px;
                font-size: 1rem;
                margin-top: 20px;
                text-align: center;
            }
             {
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
            h1 {
                text-align: center;
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
    <div class="sidebar">
        <h2>Menu</h2>
        <button onclick="window.location.href='depositar.php'">Fazer Deposito</button>
        <button onclick="window.location.href='levantar.php'">Levantar</button>
        <button onclick="window.location.href='home.php'">Consultar Saldo</button>
        <button onclick="window.location.href='transferir.php'">Transferir</button>
        <button onclick="window.location.href='historico.php'">Histórico</button>
        <button onclick="window.location.href='index.php'">Sair</button>
    </div>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Transações</title>
    <style>
        body { margin-left: 5px; padding: 20px; font-family: Arial, sans-serif; }
        table { width: 50%; margin: 100px auto; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
    <body>

<table>
    <thead>
    <tr>
        <th>Data</th>
        <th>Tipo</th>
        <th>Valor</th>
        <th>Detalhes</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($transacao = $resultTransacoes->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($transacao['data_transacao']) ?></td>
            <td><?= ucfirst(htmlspecialchars($transacao['tipo_transacao'])) ?></td>
            <td><?= number_format($transacao['valor'], 2) ?> Kz</td>
            <td><?= htmlspecialchars($transacao['detalhes']) ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<a href="home.php">Voltar</a>
</body>
</html>

