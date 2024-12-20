<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

// Captura a mensagem de erro, se existir
$loginError = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : null;
// Limpa a mensagem de erro após exibi-la
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco Digital - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        h1 {
            font-size: 2rem;
            color: #202124;
        }
        form {
            margin-top: 20px;
            width: 90%;
            max-width: 400px;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 24px;
            outline: none;
            box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }
        input[type="password"]:focus {
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
        }
        button {
            margin-top: 20px;
            padding: 10px 30px;
            background-color: #4285f4;
            color: #fff;
            border: none;
            border-radius: 24px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }
        button:hover {
            background-color: #357ae8;
        }
        .error {
            color: #d93025;
            font-size: 0.9rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<h1>Bem-vindo, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
<form action="authenticate.php" method="POST">
    <input type="password" name="password" placeholder="Digite sua senha" required>
    <button type="submit">Entrar</button>
    <?php if ($loginError): ?>
        <div class="error"><?= htmlspecialchars($loginError) ?></div>
    <?php endif; ?>
</form>
</body>
</html>
