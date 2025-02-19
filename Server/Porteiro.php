<?php

session_start();

require_once 'Server.php';


if(isset($_POST['action'])){
    if($_POST['action'] == 'login'){
        $email = $_POST['Email'];
        $senha = $_POST['Senha'];
        $res = login($email, $senha);
        if($res == "sucesso"){
            //limpa o erro e o sucesso
            unset($_SESSION['erro']);
            unset($_SESSION['sucesso']);
            header('Location: ../Home/Home.php');
        }else{
            $_SESSION['erro'] = $res;
            header('Location: ../Porteiro/Login.php');
        }
    }

    if($_POST['action'] == 'Cadastro'){
        $email = $_POST['Email'];
        $senha = $_POST['Senha'];
        $nome = $_POST['Nome'];
        $data_naci = $_POST['Data_naci'];
        $niki = $_POST['Niki'];
        $res = Cadastro($email, $senha, $nome, $data_naci, $niki);    
        if($res == "sucesso"){
            $_SESSION['sucesso'] = "Cadastro realizado com sucesso";
            header('Location: ../Porteiro/Login.php');
        }else{
            $_SESSION['erro'] = $res;
            header('Location: ../Porteiro/Cadastro.php');
        }
    }
    if($_POST['action'] == 'Atualizar'){
        $ID = $_SESSION['ID'];
        $res = Atualizar($ID);
        if($res == "sucesso"){
            $_SESSION['sucesso'] = "Atualização realizada com sucesso";
            header('Location: ../Porteiro/Perfil.php');
        }else{
            $_SESSION['erro'] = $res;
            header('Location: ../Porteiro/Perfil.php');
        }
    }
    if($_POST['action'] == 'Upload_IMG'){
        echo "Upload_IMG";
        //verifica se o arquivo foi enviado
        if(!isset($_FILES['file'])){
            $_SESSION['erro'] = "Arquivo não encontrado";
            header('Location: ../Home/Perfil.php');
        }
        //verifica se o arquivo é uma imagem
        if(!in_array($_FILES['file']['type'], ['image/gif', 'image/jpeg', 'image/png', 'image/jpg'])){
            $_SESSION['erro'] = "Arquivo não é uma imagem valida\n Tipos validos: gif, jpg, png, jpeg";
            header('Location: ../Home/Perfil.php');
        }else{
            $ID = $_SESSION['ID'];
            $res = Upload_IMG($ID, $_FILES['file']);
            if($res == "sucesso"){
                $_SESSION['sucesso'] = "Upload realizado com sucesso";
                header('Location: ../Home/Perfil.php');
            }else{
                $_SESSION['erro'] = $res;
                header('Location: ../Home/Perfil.php');
            }
        }
    }
    

}
if (isset($_GET['action']) && $_GET['action'] == 'Sair') {
    // Destrói a sessão
    session_destroy();
    
    // Redireciona para a página de login
    header('Location: ../Porteiro/Login.php');
    exit(); // Encerra a execução do script
}

if(isset($_POST['action']) && $_POST['action'] == 'Upload_IMG'){
    echo "Upload_IMG";
    if(!isset($_FILES['file'])){
        $_SESSION['erro'] = "Arquivo não encontrado";
        header('Location: ../Home/Perfil.php');
        exit();
    }

    $ID = $_SESSION['ID'];
    $res = Upload_IMG($ID, $_FILES['file']);

    if($res == "sucesso"){
        $_SESSION['sucesso'] = "Upload realizado com sucesso";
    } else {
        $_SESSION['erro'] = $res;
    }

    header('Location: ../Home/Perfil.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'Atualizar_img') {
    $img = $_GET['img'];
    $ID = $_SESSION['ID'];
    Atualizar_img($ID, $img);
    Atualizar_sessao();
    header('Location: ../Home/Perfil.php');
    exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'Novo_post') {
    $ID = $_SESSION['ID'];
    $conteudo = $_POST['Conteudo'];
    $res = NovoPost($ID, $conteudo);
    if ($res == "sucesso") {
        // Enviar resposta JSON com sucesso
        echo json_encode(['status' => 'sucesso']);
    } else {
        // Enviar resposta JSON com erro
        echo json_encode(['status' => 'erro', 'message' => $res]);
    }
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'comentar') {
    $post_id = $_GET['post_id'];
    $id_user = $_GET['id_user'];
    $file = $_GET['file'];
    $comentario = $_GET['comentario'];
    Comentar($id_user, $post_id, $file, $comentario);
    header('Location: ../Home/Home.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'Like') {
    $ID_user = $_GET['ID_user'];
    $ID_post = $_GET['ID_post'];
    $file = $_GET['file'];
    Like($ID_user, $ID_post, $file);
    header('Location: ../Home/Home.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'Dislike') {
    $ID_user = $_GET['ID_user'];
    $ID_post = $_GET['ID_post'];
    $file = $_GET['file'];
    Dislike($ID_user, $ID_post, $file);
    header('Location: ../Home/Home.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'Atualizar_sessao') {
    Atualizar_sessao();
    header('Location: ../Home/Perfil.php');
    exit();
}


?>