<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISPPUDIG - Inicio</title>
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
            font-size: 2.5rem;
            color: #202124;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
            width: 90%;
            max-width: 400px;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px 20px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 24px;
            outline: none;
            box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }
        input[type="text"]:focus {
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
<h1>Verificação de Identidade</h1>
<form action="verify.php" method="POST">
    <input type="text" name="document_number" placeholder="Digite o Nº de BI ou Passaporte" required>
    <button type="submit">Verificar</button>
</form>
</body>
</html>
