<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/Login.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="Login">
            <h1>Login</h1>
            <form action="Login.php" method="post">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            <button class="btn" type="submit">Entrar</button>
        </form>
        <div class="links">
            <a href="Cadastro.php">Cadastre-se</a>
            <a href="RecuperarSenha.php">Esqueceu sua senha?</a>
        </div>
    </div>    
</body>
</html>