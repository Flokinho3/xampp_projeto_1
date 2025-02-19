<?php
/**
 * Tabela usuarios
 * 
 * ID (int) - Chave primária, auto incremento
 * Email (text) - Email do usuário
 * Senha (text) - Senha criptografada
 * Niki (text) - Apelido/nickname do usuário
 * Img (text) - Caminho da imagem de perfil, padrão 'CSS/img/Fundo_padrao.jpeg'
 * Data_naci (date) - Data de nascimento
 * Nome (text) - Nome completo
 */

/**

 */

function conexao(){
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "registro";
    if($con = new mysqli($host, $user, $pass, $db)){
        return $con;
    }
}

function login($email, $senha) {
    $con = conexao();
    
    // Usar prepared statements para evitar injeção de SQL
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (password_verify($senha, $row['Senha'])) {
            $_SESSION['email'] = $email;   
            $_SESSION['nome'] = $row['Nome'];
            $_SESSION['data_naci'] = $row['Data_naci'];
            $_SESSION['niki'] = $row['Niki'];
            $_SESSION['img'] = $row['Img'];
            $_SESSION['ID'] = $row['ID'];
            return "sucesso";
        } else {
            return "Senha incorreta";
        }
    } else {
        return "Email não encontrado";
    }
}

function Cadastro($email, $senha, $nome, $data_naci, $niki) {
    $con = conexao();
    $senha = password_hash($senha, PASSWORD_DEFAULT);
    
    // Verificar se o email já existe
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        return "Email já cadastrado";
    }
    
    // Verificar se o niki já existe
    $sql = "SELECT * FROM usuarios WHERE niki = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $niki);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        return "Niki já cadastrado";
    }
    
    // Inserir no banco de dados
    $sql = "INSERT INTO usuarios (email, senha, nome, data_naci, niki) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $email, $senha, $nome, $data_naci, $niki);
    
    if ($stmt->execute()) {
        return "sucesso";
    } else {
        return "Erro ao cadastrar";
    }
}

function Upload_IMG($ID, $file){
    $con = conexao();
    // Verificar se houve erro no upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['erro'] = "Erro no upload do arquivo";
        header('Location: ../Home/Perfil.php');
        exit();
    }
    // Verificar se o arquivo foi enviado
    if (!is_uploaded_file($file['tmp_name'])) {
        $_SESSION['erro'] = "Arquivo não encontrado";
        header('Location: ../Home/Perfil.php');
        exit();
    }
    $extensoes_permitidas = ['gif', 'jpg', 'jpeg', 'png'];
    $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoes_permitidas)) {
        $_SESSION['erro'] = "Extensão de arquivo não permitida!";
        header('Location: ../Home/Perfil.php');
        exit();
    }

    // Verifica se realmente é uma imagem
    if (!getimagesize($file['tmp_name'])) {
        $_SESSION['erro'] = "O arquivo enviado não é uma imagem válida.";
        header('Location: ../Home/Perfil.php');
        exit();
    }

    // Verifica se a pasta do usuário existe
    if (!file_exists("../Home/USERS/".$ID)) {
        // Senão cria a pasta
        mkdir("../Home/USERS/".$ID, 0755, true);
    }
    // Verifica se a pasta de imagens do usuário existe
    if (!file_exists("../Home/USERS/".$ID."/IMGS/")) {
        // Senão cria a pasta
        mkdir("../Home/USERS/".$ID."/IMGS/", 0755, true);
    }
    // Renomeia o arquivo
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = $ID."_".time().".".$ext;
    // Move o arquivo para a pasta de imagens do usuário
    if (move_uploaded_file($file['tmp_name'], "../Home/USERS/".$ID."/IMGS/".$fileName)) {
        // Atualiza o campo img do usuário
        $sql = "UPDATE usuarios SET Img = ? WHERE ID = ?";
        $stmt = $con->prepare($sql);
        $imgPath = "Home/USERS/".$ID."/IMGS/".$fileName;
        $stmt->bind_param("si", $imgPath, $ID);
        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Upload realizado com sucesso";
            header('Location: ../Home/Perfil.php');
            exit();
        } else {
            $_SESSION['erro'] = "Erro ao atualizar";
            header('Location: ../Home/Perfil.php');
            exit();
        }
    } else {
        $_SESSION['erro'] = "Erro ao mover o arquivo";
        header('Location: ../Home/Perfil.php');
        exit();
    }
}

function Atualizar_sessao(){
    session_start();
    $ID = $_SESSION['ID']; // Armazena o ID antes de destruir a sessão
    session_destroy();
    session_start();
    
    $con = conexao();
    $sql = "SELECT * FROM usuarios WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if($row){
        $_SESSION['nome'] = $row['Nome'];
        $_SESSION['email'] = $row['Email'];
        $_SESSION['data_naci'] = $row['Data_naci'];
        $_SESSION['niki'] = $row['Niki'];
        $_SESSION['img'] = $row['Img'];
        $_SESSION['ID'] = $row['ID'];
        $_SESSION['sucesso'] = "Sessão atualizada com sucesso";
        header('Location: Perfil.php');
    } else {
        $_SESSION['erro'] = "Erro ao atualizar a sessão";
        header('Location: Perfil.php');
    }
}

function Atualizar_img($ID, $img){
    $con = conexao();
    $sql = "UPDATE usuarios SET Img = ? WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $img, $ID);
    $stmt->execute();
    if($stmt->execute()){
        $_SESSION['sucesso'] = "Imagem atualizada com sucesso";
        header('Location: ../Home/Perfil.php');
    }else{
        $_SESSION['erro'] = "Erro ao atualizar a imagem";
        header('Location: ../Home/Perfil.php');
    }
}

// Função já declarada em Porteiro.php
if (!function_exists('Pesquisar_img_user')) {
    function Pesquisar_img_user($ID){
        $con = conexao();
        $sql = "SELECT * FROM usuarios WHERE ID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Img'];
    }
}

function randomico($length = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

function Tamplate_post($ID, $conteudo){
    $con = conexao();
    $sql = "SELECT * FROM usuarios WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $post = array(
        "ID_user" => $ID,
        "ID_post" => randomico(),
        "Nome" => $row['Nome'],
        "Email" => $row['Email'],
        "Conteudo" => $conteudo,
        "Data" => date("Y-m-d H:i:s"),
        "Like" => 0,
        "Like_user" => array(),
        "Dislike" => 0,
        "Dislike_user" => array(),
        "Comentario" => 0,
        "Comentario_user" => array(),
    );
    return $post;
}

function Ultimos_posts($ID, $ID_post, $FILE_USER, $FILE){
    // Verifica se o arquivo principal já existe
    if (file_exists($FILE)) {
        $posts = json_decode(file_get_contents($FILE), true);
    } else {
        $posts = array(); // Cria um array vazio se o arquivo não existir
    }

    // Adiciona o novo post ao array
    $posts[] = array(
        "ID_user" => $ID,
        "File" => $FILE_USER,
        "ID_post" => $ID_post,
    );

    // Salva o array atualizado no arquivo JSON principal
    file_put_contents($FILE, json_encode($posts));
}

function NovoPost($ID, $conteudo){
    $con = conexao();
    $FILE = "../Home/USERS/Atualizacoes.json"; // Arquivo principal
    $FILE_USER = "../Home/USERS/".$ID."/Puble/".time().".json"; // Arquivo do post

    // Cria o diretório se não existir
    if(!file_exists("../Home/USERS/".$ID."/Puble/")){
        mkdir("../Home/USERS/".$ID."/Puble/", 0755, true);
    }

    // Cria o template do post
    $post = Tamplate_post($ID, $conteudo);
    $json = json_encode($post);

    // Salva o post no arquivo do usuário
    file_put_contents($FILE_USER, $json);

    // Atualiza o arquivo principal com o novo post
    Ultimos_posts($ID, $post['ID_post'], $FILE_USER, $FILE);
    header('Location: ../Home/Home.php');
}

function Pesquisar_nome_user($ID){
    $con = conexao();
    $sql = "SELECT Nome FROM usuarios WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['Nome'];
}

function Dislike($ID_user, $ID_post, $FILE){
    $con = conexao();
        //verificar se o arquivo existe 
    if(file_exists($FILE)){
        $posts = json_decode(file_get_contents($FILE), true);
    }else{
        $_SESSION['erro'] = "Erro ao curtir";
        header('Location: ../Home/Home.php');
        exit();
    }
    //verificar se o post existe
    if(isset($posts[$ID_post])){
        $posts[$ID_post]['Dislike']++;
        $posts[$ID_post]['Dislike_user'][] = $ID_user;
    }else{
        $_SESSION['erro'] = "Erro ao curtir";
        header('Location: ../Home/Home.php');
        exit();
    }
    //salvar o post
    file_put_contents($FILE, json_encode($posts));
    header('Location: ../Home/Home.php');
}   

function Like($ID_user, $ID_post, $FILE) {
    // Verificar se o arquivo existe e está acessível
    if (!file_exists($FILE)) {
        $_SESSION['erro'] = "Erro ao curtir: Arquivo não encontrado.";
        header('Location: ../Home/Home.php');
        exit();
    }

    // Carregar o conteúdo do arquivo JSON
    $posts_json = file_get_contents($FILE);
    $posts = json_decode($posts_json, true);

    // Verificar se a decodificação do JSON foi bem-sucedida
    if ($posts === null) {
        $_SESSION['erro'] = "Erro ao curtir: Dados inválidos no arquivo.";
        header('Location: ../Home/Home.php');
        exit();
    }

    // Verificar se o post existe no arquivo
    if (!isset($posts[$ID_post])) {
        $_SESSION['erro'] = "Erro ao curtir: Post não encontrado.";
        header('Location: ../Home/Home.php');
        exit();
    }

    // Adicionar o like e o usuário que deu o like
    $posts[$ID_post]['Like']++;
    $posts[$ID_post]['Like_user'][] = $ID_user;

    // Salvar as modificações no arquivo
    if (file_put_contents($FILE, json_encode($posts, JSON_PRETTY_PRINT))) {
        // Sucesso! Redirecionar para a página inicial
        header('Location: ../Home/Home.php');
        exit();
    } else {
        // Erro ao salvar o arquivo
        $_SESSION['erro'] = "Erro ao curtir: Falha ao salvar o arquivo.";
        header('Location: ../Home/Home.php');
        exit();
    }
}



function Comentar($ID_user, $ID_post, $FILE, $comentario) {
    $con = conexao();

    // Verifica se o post e o usuário são válidos
    if (!is_numeric($ID_post) || !is_numeric($ID_user)) {
        $_SESSION['erro'] = "Dados inválidos";
        header('Location: ../Home/Home.php');
        exit();
    }

    // Verificar se o arquivo existe
    if(file_exists($FILE)){
        $posts = json_decode(file_get_contents($FILE), true);
    }else{
        $_SESSION['erro'] = "Erro ao comentar";
        header('Location: ../Home/Home.php');
        exit();
    }

    // Verificar se o post existe
    if(isset($posts[$ID_post])) {
        $posts[$ID_post]['Comentario']++;
        $posts[$ID_post]['Comentario_user'][] = $ID_user;
        $posts[$ID_post]['Comentarios'][] = $comentario; // Armazena o comentário
    } else {
        $_SESSION['erro'] = "Erro ao comentar";
        header('Location: ../Home/Home.php');
        exit();
    }

    // Salvar o post com o novo comentário
    file_put_contents($FILE, json_encode($posts));
    header('Location: ../Home/Home.php');
}

?>