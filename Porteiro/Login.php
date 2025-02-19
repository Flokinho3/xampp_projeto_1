<?php

session_start();

include '../Server/Alert.php';

Exibir_Alertas();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Porteiro</title>
    <link rel="stylesheet" href="../CSS/Login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../CSS/Alerta.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <h1>Login - Porteiro</h1>
        <form action="../Server/Porteiro.php" method="post">
            <input type="hidden" name="action" value="login">
            <input type="email" name="Email" placeholder="Email" required>
            <input type="password" name="Senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>  
        <a href="Cadastro.php">Cadastro</a>   
        <a href="Recuperar_Senha.php">Recuperar Senha</a>
    </div>
</body>
</html>