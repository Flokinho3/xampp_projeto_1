<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="../CSS/RecuperarSenha.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="RecuperarSenha">
            <h1>Recuperar Senha</h1>
            <form action="RecuperarSenha.php" method="post">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <button class="btn" type="submit">Recuperar</button>
            </form>
        </div>
        <div class="links">
            <a href="Login.php">Já tem uma conta? Faça login</a>
            <a href="Cadastro.php">Não tem uma conta? Cadastre-se</a>
        </div>
    </div>
</body>
</html>