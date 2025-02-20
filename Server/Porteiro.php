<?php

include 'Serve.php';

// Conecta ao banco de dados
$pdo = conectar_banco();

// Verifica se o método é POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tipo'])) {

    // Verifica se o tipo é cadastro
    if ($_POST['tipo'] == 'cadastro') {
        // Validações básicas (pode adicionar mais)
        if (empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['nome']) || empty($_POST['nickname'])) {
            $_SESSION['erro'] = "Todos os campos são obrigatórios!";
            header("Location: ../Porteiro/Cadastro.php");
            exit;
        }

        $email = $_POST['email'];
        $senha = $_POST['senha']; // Pega a senha do POST
        $nome = $_POST['nome'];
        $nickname = $_POST['nickname'];

        // Chama a função de cadastro
        Cadastro($pdo, $email, $senha, $nome, $nickname);
    }
} else {
    // Se não for POST ou 'tipo' não estiver definido
    $_SESSION['erro'] = "Requisição inválida!";
    header("Location: ../Porteiro/Cadastro.php"); // Redireciona para a página de cadastro
    exit;
}
?>