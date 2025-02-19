<?php
session_start();
require_once '../Server/Alert.php';
Exibir_Alertas();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content= "width=device-width, initial-scale=1.0">
    <title>Cadastro - Porteiro</title>
    <link rel="stylesheet" href="../CSS/Cadastro.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../CSS/Alerta.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <h1>Cadastro - Porteiro</h1>
        <form action="../Server/Porteiro.php" method="post">
            <input type="hidden" name="action" value="Cadastro">
            <input type="email" name="Email" placeholder="Email" required>
            <input type="password" name="Senha" placeholder="Senha" required>
            <input type="text" name="Nome" placeholder="Nome" required>
            <input type="date" name="Data_naci" placeholder="Data de nascimento" required>
            <input type="text" name="Niki" placeholder="Niki" required>
            <button type="submit">Cadastrar</button>
        </form>
        <a href="Login.php">Já tem uma conta? Faça login</a>    
        <a href="Recuperar_Senha.php">Esqueceu sua senha?</a>
    </div>
</body>
</html>
    
    



