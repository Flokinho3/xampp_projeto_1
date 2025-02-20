<?php

// Verifica se tem uma sessão ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function conectar_banco() {
    $host = getenv('DB_HOST') ?: "dpg-cuqm2abqf0us73f34g2g-a.oregon-postgres.render.com"; 
    $port = getenv('DB_PORT') ?: "5432"; 
    $dbname = getenv('DB_NAME') ?: "registro_mrui"; 
    $user = getenv('DB_USER') ?: "registro_mrui_user"; 
    $password = getenv('DB_PASSWORD') ?: "tbKXrTsOGpZhrcuPQIZOWDkFOHiyXgAp"; 

    // String de conexão PDO
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

    try {
        // Criando conexão com PDO
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        return $pdo;
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}

function Cadastro($pdo, $email, $senha, $nome, $nickname) {
    // Verifica se o email já está cadastrado
    $stmt = $pdo->prepare("SELECT 1 FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['erro'] = "Email já cadastrado!";
        header("Location: ../Porteiro/Cadastro.php");
        exit;
    }

    // Verifica se o nickname já está cadastrado
    $stmt = $pdo->prepare("SELECT 1 FROM usuarios WHERE nickname = ?");
    $stmt->execute([$nickname]);
    if ($stmt->fetch()) {
        $_SESSION['erro'] = "Nickname já cadastrado!";
        header("Location: ../Porteiro/Cadastro.php");
        exit;
    }

    // Verifica se o nome já está cadastrado
    $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE nome = ?");
    $stmt->execute([$nome]);

    if ($stmt->fetch()) {
        do {
            // Garante que o nome já não contém um sufixo
            $nomeBase = explode("#", $nome)[0]; 
            $nome = $nomeBase . "#" . rand(1000, 9999);

            $stmt = $pdo->prepare("SELECT 1 FROM usuarios WHERE nome = ?");
            $stmt->execute([$nome]);
        } while ($stmt->fetch());
    }

    // Hash seguro da senha
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // Inserção no banco
    $stmt = $pdo->prepare("INSERT INTO usuarios (email, senha, nome, nickname) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $senha_hash, $nome, $nickname]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
        header("Location: ../Porteiro/Login.php");
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao cadastrar!";
        header("Location: ../Porteiro/Cadastro.php");
        exit;
    }
}

//pghero-5fee90d1
//0377f506e383fe4ff7e1a79b8f18cf98
?>
