<?php
session_start();
if (!isset($_SESSION['document_number'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
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
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 24px;
            outline: none;
            box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            transition: box-shadow 0.3s ease-in-out;
        }
        input:focus {
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
    </style>
</head>
<body>
<h1>Cadastro</h1>
<form action="save_user.php" method="POST">
    <input type="text" name="bi_passaporte" value="<?= htmlspecialchars($_SESSION['document_number']) ?>" readonly>
    <input type="text" name="nome_completo" placeholder="Nome Completo" required>
    <input type="text" name="telefone" placeholder="Nº de Telefone" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="morada" placeholder="Morada" required>
    <input type="password" name="senha" placeholder="Criar Senha" required>
    <button type="submit">Cadastrar</button>
</form>
</body>
</html>

