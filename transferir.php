<?php
session_start();
if (!isset($_SESSION['usuario_id']))

    $conn = new mysqli('localhost', 'root', '', 'banco_digital');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

//Verificar conexão

// Inicializa variáveis
$mensagem = "";
$destinatario = null;
$codigo_unico = isset($_POST['codigo_unico']) ? $_POST['codigo_unico'] : null;
$valor_transferencia = isset($_POST['valor_transferencia']) ? $_POST['valor_transferencia'] : null;
$usuario_id_origem = 1;

// Busca o destinatário pelo código único (em ambas as submissões)
if ($codigo_unico) {
    $sql = "SELECT id, nome_completo, saldo FROM usuarios WHERE codigo_unico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo_unico);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $destinatario = $result->fetch_assoc();
    } else {
        $mensagem = "Código único não encontrado.";
    }
}

// Processar transferência somente se o destinatário for encontrado
if ($valor_transferencia && $destinatario) {
    // Verifica saldo do usuário logado
    $sql = "SELECT saldo FROM contas WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id_origem);
    $stmt->execute();
    $result = $stmt->get_result();
    $conta_origem = $result->fetch_assoc();

    if ($conta_origem['saldo'] >= $valor_transferencia) {
        $sql = "UPDATE contas SET saldo = saldo - ? WHERE usuario_id = ?";
        $stmt->bind_param("di", $valor_transferencia, $usuario_id_origem);
        $stmt->execute();

        // Atualizar saldo do destinatário
        $sql = "UPDATE contas SET saldo = saldo + ? WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $valor_transferencia, $destinatario['id']);
        $stmt->execute();

        // Registrar a transação
        $sql = "INSERT INTO transacoes (conta_origem, conta_destino, usuario_id, tipo_transacao, valor, detalhes)
                VALUES (?, ?, ?, 'transferência', ?, 'Transferência de saldo')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $usuario_id_origem, $destinatario['id'], $usuario_id_origem, $valor_transferencia);
        $stmt->execute();

        $mensagem = "Transferência realizada com sucesso!";
    } else {
       $mensagem = "Saldo insuficiente para a transferência.";
    }
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
    <p>Aqui você pode realizar transferências.</p>
    <?php if ($mensagem): ?>
        <p><?= $mensagem ?></p>
    <?php endif; ?>

    <!-- Formulário para buscar o destinatário -->
    <form method="POST">
        <label for="codigo_unico">Código único do destinatário:</label>
        <input type="text" name="codigo_unico" id="codigo_unico" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($destinatario): ?>
        <h2>Destinatário encontrado:</h2>
        <p><strong>Nome:</strong> <?= $destinatario['nome_completo'] ?></p>

        <!-- Formulário para confirmação da transferência -->
        <form method="POST">
            <input type="hidden" name="codigo_unico" value="<?= $codigo_unico ?>">
            <label for="valor_transferencia">Valor da transferência:</label>
            <input type="number" name="valor_transferencia" id="valor_transferencia" step="0.01" required>
            <button type="submit">Confirmar Transferência</button>
        </form>
    <?php endif; ?>
    <a href="home.php">Voltar</a>

</body>
</html>
