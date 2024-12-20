<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// Configuração do banco de dados
$conn = new mysqli('localhost', 'root', '', 'banco_digital');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Obtém informações do usuário
$email = $_SESSION['user_email'];
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Gera o código único do usuário, se ainda não existir
if (empty($user['codigo_unico'])) {
    $codigoUnico = uniqid("USER");
    $sqlUpdate = "UPDATE usuarios SET codigo_unico = ? WHERE email = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ss", $codigoUnico, $email);
    $stmtUpdate->execute();
    $user['codigo_unico'] = $codigoUnico;
}
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
    <button onclick="window.location.href='depositar.php'">Fazer Depósito</button>
    <button onclick="window.location.href='levantar.php'">Levantar</button>
    <button onclick="showPage('consultar_saldo')">Consultar Saldo</button>
    <button onclick="window.location.href='transferir.php'">Transferir</button>
    <button onclick="window.location.href='historico.php'">Histórico</button>
    <button onclick="window.location.href='index.php'">Sair</button>
</div>
<div class="main">
    <header class="header">
        ISPPUDIG - Bem-vindo
    </header>
    <div id="home" class="content page">
        <h1>Olá, <?= htmlspecialchars($user['nome_completo']) ?>!</h1>
        <p>O que você gostaria de fazer hoje?</p>
        <div class="codigo">
            <strong>Seu Código Único para transferências:</strong><br>
            <?= htmlspecialchars($user['codigo_unico']) ?>
        </div>
    </div>
    <?php
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
    <div id="depositar" class="content page hidden">
        <h2>Fazer Depósito</h2>
        <form method="POST">
            <input type="number" step="0.01" name="valor" placeholder="Valor a depositar" required>
            <button type="submit">Depositar</button>
        </form>
    </div>
    <div id="levantar" class="content page hidden">
        <h2>Levantar</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $valor = floatval($_POST['valor']);
            $email = $_SESSION['user_email'];

            $sql = "SELECT saldo FROM usuarios WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user['saldo'] >= $valor) {
                $sql = "UPDATE usuarios SET saldo = saldo - ? WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ds", $valor, $email);
                $stmt->execute();

                $_SESSION['mensagem'] = "Levantamento de $valor realizado com sucesso!";
                header("Location: home.php");
                exit();
            } else {
                echo "Saldo insuficiente.";
            }
        }
        // Registra a transação
        $sqlTransacao = "INSERT INTO transacoes (usuario_id, tipo_transacao, valor, detalhes) VALUES (?, 'levantamento', ?, 'Levantamento realizado')";
        $stmtTransacao = $conn->prepare($sqlTransacao);
        $stmtTransacao->bind_param("id", $user['id'], $valor);
        $stmtTransacao->execute();
        ?>
        <form method="POST">
            <form id="formulario">
                <input type="number" step="0.01" name="valor" placeholder="Valor a levantar" required max="-0.01">
                <button type="submit">Levantar</button>
            </form>
        </form>
    </div>
    <div id="consultar_saldo" class="content page hidden">
        <h2>Consultar Saldo</h2>
        <?php
        $sqlSaldo = "SELECT saldo FROM usuarios WHERE email = ?";
        $stmtSaldo = $conn->prepare($sqlSaldo);
        $stmtSaldo->bind_param("s", $email);
        $stmtSaldo->execute();
        $resultSaldo = $stmtSaldo->get_result();
        $userSaldo = $resultSaldo->fetch_assoc();
        ?>
        <p>Seu saldo é: <strong><?= number_format($userSaldo['saldo'], 2) ?> Kz</strong></p>
    </div>
    <div id="transferir" class="content page hidden">
        <h2>Transferir</h2>
        <p>Aqui você pode realizar transferências.</p>
        <form method="POST">
            <input type="text" name="codigo_destino" placeholder="Código do Destinatário" required>
            <input type="number" step="0.01" name="valor" placeholder="Valor a transferir" required>
            <button type="submit">Transferir</button>
        </form>
    </div>
    <div id="historico" class="content page hidden">

        <h2>Histórico</h2>
        <p>Aqui você pode consultar o histórico de suas transações.</p>
        <?php
        // Conectar ao banco de dados
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "banco_digital"; // Nome do banco de dados

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar a conexão
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Consultar as transações
        $sql = "SELECT data_hora, tipo_transacao, detalhes, valor FROM transacoes ORDER BY data_hora DESC";
        $result = $conn->query($sql);

        ?>

                <div class="container">
            <h1>Histórico de Transações</h1>

            <?php
            if ($result->num_rows > 0) {
                // Exibir as transações
                echo "<table class='transacoes'>
                    <thead>
                        <tr>
                            <th>Data e Hora</th>
                            <th>Tipo de Transação</th>
                            <th>Detalhes</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>";

                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row["data_hora"] . "</td>
                        <td>" . ucfirst($row["tipo_transacao"]) . "</td>
                        <td>" . $row["detalhes"] . "</td>
                        <td>R$ " . number_format($row["valor"], 2, ',', '.') . "</td>
                      </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p>Nenhuma transação encontrada.</p>";
            }
            ?>

        </div>


        <?php
        $conn->close();
        ?>


    </div>
</div>

<footer>
    <strong>Código Único:</strong><?= htmlspecialchars($user['codigo_unico']) ?></br>
    &copy; 2024 ISPPUDIG. Todos os direitos reservados.
</footer>
</body>
</html>
