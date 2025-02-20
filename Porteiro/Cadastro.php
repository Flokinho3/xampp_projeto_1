<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../CSS/Cadastro.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="Alerta">
        <p>Projeto em desenvolvimento Nao informe dados reais!</p>
    </div>
    <div class="container">
        <div class="Login">
            <h1>Cadastro</h1>
            <form action="../Server/Porteiro.php" method="post">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" required>
                <label for="nickname">Nickname:</label>
                <input type="text" name="nickname" id="nickname" required>
                <input type="hidden" name="tipo" value="cadastro">
                <button class="btn" type="submit">Cadastrar</button>
            </form>
        </div>
        <div class="links">
            <a href="Login.php">Já tem uma conta? Faça login</a>
            <a href="RecuperarSenha.php">Esqueceu sua senha?</a>
        </div>
    </div>
</body>
</html>