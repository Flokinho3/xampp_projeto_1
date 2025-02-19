<?php
//verifica se ja tem uma sessão iniciada
if (!isset($_SESSION)) {
    session_start();
}

/* ========================================================
   Funções para manipulação dos usuários via arquivo JSON
   ======================================================== */

// Define o caminho do arquivo JSON de usuários
define('USERS_FILE', __DIR__ . '/usuarios.json');

// Carrega os usuários a partir do arquivo JSON
function load_users() {
    if (!file_exists(USERS_FILE)) {
        // Se o arquivo não existir, cria-o com um array vazio
        file_put_contents(USERS_FILE, json_encode([]));
    }
    $json = file_get_contents(USERS_FILE);
    $users = json_decode($json, true);
    if ($users === null) {
        $users = [];
    }
    return $users;
}

// Salva o array de usuários no arquivo JSON
function save_users($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

// Retorna o próximo ID disponível (auto incremento)
function get_new_user_id($users) {
    if (empty($users)) {
        return 1;
    }
    $max = 0;
    foreach($users as $user) {
        if ($user['ID'] > $max) {
            $max = $user['ID'];
        }
    }
    return $max + 1;
}

// Função de cadastro de usuário usando JSON
function Cadastro($email, $senha, $nome, $data_naci, $niki) {
    $users = load_users();
    
    // Verifica se o email ou niki já existem
    foreach ($users as $user) {
        if ($user['Email'] === $email) {
            return "Email já cadastrado";
        }
        if ($user['Niki'] === $niki) {
            return "Niki já cadastrado";
        }
    }
    
    $new_id = get_new_user_id($users);
    $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
    
    $new_user = [
        "ID"         => $new_id,
        "Email"      => $email,
        "Senha"      => $hashed_password,
        "Niki"       => $niki,
        "Img"        => 'CSS/img/Fundo_padrao.jpeg',
        "Data_naci"  => $data_naci,
        "Nome"       => $nome,
    ];
    
    $users[] = $new_user;
    save_users($users);
    
    return "sucesso";
}

// Função de login usando JSON
function login($email, $senha) {
    $users = load_users();
    
    foreach($users as $user) {
        if ($user['Email'] === $email) {
            if (password_verify($senha, $user['Senha'])) {
                $_SESSION['email']     = $user['Email'];
                $_SESSION['nome']      = $user['Nome'];
                $_SESSION['data_naci'] = $user['Data_naci'];
                $_SESSION['niki']      = $user['Niki'];
                $_SESSION['img']       = $user['Img'];
                $_SESSION['ID']        = $user['ID'];
                return "sucesso";
            } else {
                return "Senha incorreta";
            }
        }
    }
    return "Email não encontrado";
}

// Pesquisa o nome do usuário pelo ID
function Pesquisar_nome_user($ID) {
    $users = load_users();
    foreach ($users as $user) {
        if ($user['ID'] == $ID) {
            return $user['Nome'];
        }
    }
    return null;
}

// Pesquisa o email do usuário pelo ID
function Pesquisar_email_user($ID) {
    $users = load_users();
    foreach ($users as $user) {
        if ($user['ID'] == $ID) {
            return $user['Email'];
        }
    }
    return null;
}

// Pesquisa a imagem do usuário pelo ID
function Pesquisar_img_user($ID) {
    $users = load_users();
    foreach ($users as $user) {
        if ($user['ID'] == $ID) {
            return $user['Img'];
        }
    }
    return null;
}

// Atualiza a imagem do usuário e salva no JSON
function Atualizar_img($ID, $img) {
    $users = load_users();
    foreach ($users as &$user) {
        if ($user['ID'] == $ID) {
            $user['Img'] = $img;
            save_users($users);
            $_SESSION['sucesso'] = "Imagem atualizada com sucesso";
            header('Location: ../Home/Perfil.php');
            exit();
        }
    }
    $_SESSION['erro'] = "Erro ao atualizar a imagem";
    header('Location: ../Home/Perfil.php');
    exit();
}

// Atualiza os dados da sessão (recarrega os dados do usuário)
function Atualizar_sessao(){
    if (!isset($_SESSION['ID'])) {
        $_SESSION['erro'] = "Sessão inválida";
        header('Location: ../Home/Perfil.php');
        exit();
    }
    $ID = $_SESSION['ID'];
    session_destroy();
    session_start();
    
    $users = load_users();
    foreach ($users as $user) {
        if ($user['ID'] == $ID) {
            $_SESSION['nome']      = $user['Nome'];
            $_SESSION['email']     = $user['Email'];
            $_SESSION['data_naci'] = $user['Data_naci'];
            $_SESSION['niki']      = $user['Niki'];
            $_SESSION['img']       = $user['Img'];
            $_SESSION['ID']        = $user['ID'];
            $_SESSION['sucesso']   = "Sessão atualizada com sucesso";
            header('Location: ../Home/Perfil.php');
            exit();
        }
    }
    $_SESSION['erro'] = "Erro ao atualizar a sessão";
    header('Location: ../Home/Perfil.php');
    exit();
}

/* ========================================================
   Funções para manipulação de imagens (upload)
   ======================================================== */

function Upload_IMG($ID, $file){
    // Verifica se houve erro no upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['erro'] = "Erro no upload do arquivo";
        header('Location: ../Home/Perfil.php');
        exit();
    }
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
    if (!getimagesize($file['tmp_name'])) {
        $_SESSION['erro'] = "O arquivo enviado não é uma imagem válida.";
        header('Location: ../Home/Perfil.php');
        exit();
    }
    // Cria a pasta do usuário, se não existir
    $userDir = "../Home/USERS/".$ID;
    if (!file_exists($userDir)) {
        mkdir($userDir, 0755, true);
    }
    // Cria a pasta de imagens do usuário, se não existir
    $imgDir = $userDir."/IMGS/";
    if (!file_exists($imgDir)) {
        mkdir($imgDir, 0755, true);
    }
    $fileName = $ID . "_" . time() . "." . $extensao;
    $destino = $imgDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $destino)) {
        // Atualiza o caminho da imagem no JSON
        $imgPath = "Home/USERS/".$ID."/IMGS/".$fileName;
        Atualizar_img($ID, $imgPath);
    } else {
        $_SESSION['erro'] = "Erro ao mover o arquivo";
        header('Location: ../Home/Perfil.php');
        exit();
    }
}

/* ========================================================
   Funções para manipulação de posts via JSON
   ======================================================== */

// Gera uma string aleatória para o ID do post
function randomico($length = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

// Cria um template para o post com os dados do usuário
function Tamplate_post($ID, $conteudo){
    $nome  = Pesquisar_nome_user($ID);
    $email = Pesquisar_email_user($ID);
    $post = array(
        "ID_user"         => $ID,
        "ID_post"         => randomico(),
        "Nome"            => $nome,
        "Email"           => $email,
        "Conteudo"        => $conteudo,
        "Data"            => date("Y-m-d H:i:s"),
        "Like"            => 0,
        "Like_user"       => array(),
        "Dislike"         => 0,
        "Dislike_user"    => array(),
        "Comentario"      => 0,
        "Comentario_user" => array(),
        "Comentarios"     => array()
    );
    return $post;
}

// Atualiza o arquivo principal com informações resumidas dos posts
function Ultimos_posts($ID, $ID_post, $FILE_USER, $FILE){
    if (file_exists($FILE)) {
        $posts = json_decode(file_get_contents($FILE), true);
    } else {
        $posts = array();
    }
    $posts[] = array(
        "ID_user" => $ID,
        "File"    => $FILE_USER,
        "ID_post" => $ID_post,
    );
    file_put_contents($FILE, json_encode($posts, JSON_PRETTY_PRINT));
}

// Cria um novo post e o salva em arquivo JSON
function NovoPost($ID, $conteudo){
    $FILE = "../Home/USERS/Atualizacoes.json"; // Arquivo principal de atualizações
    $FILE_USER = "../Home/USERS/".$ID."/Puble/".time().".json"; // Arquivo do post
    
    // Cria o diretório para os posts do usuário, se não existir
    $userPostDir = "../Home/USERS/".$ID."/Puble/";
    if (!file_exists($userPostDir)){
        mkdir($userPostDir, 0755, true);
    }
    
    $post = Tamplate_post($ID, $conteudo);
    $json = json_encode($post, JSON_PRETTY_PRINT);
    
    // Salva o post no arquivo do usuário
    file_put_contents($FILE_USER, $json);
    
    // Atualiza o arquivo principal com o novo post
    Ultimos_posts($ID, $post['ID_post'], $FILE_USER, $FILE);
    header('Location: ../Home/Home.php');
    exit();
}

// Função para adicionar um "like" a um post
function Like($ID_user, $ID_post, $FILE) {
    if (!file_exists($FILE)) {
        $_SESSION['erro'] = "Erro ao curtir: Arquivo não encontrado.";
        header('Location: ../Home/Home.php');
        exit();
    }
    $posts = json_decode(file_get_contents($FILE), true);
    if ($posts === null) {
        $_SESSION['erro'] = "Erro ao curtir: Dados inválidos no arquivo.";
        header('Location: ../Home/Home.php');
        exit();
    }
    $found = false;
    foreach($posts as &$post) {
        if ($post['ID_post'] == $ID_post) {
            $post['Like']++;
            $post['Like_user'][] = $ID_user;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['erro'] = "Erro ao curtir: Post não encontrado.";
        header('Location: ../Home/Home.php');
        exit();
    }
    if (file_put_contents($FILE, json_encode($posts, JSON_PRETTY_PRINT))) {
        header('Location: ../Home/Home.php');
        exit();
    } else {
        $_SESSION['erro'] = "Erro ao curtir: Falha ao salvar o arquivo.";
        header('Location: ../Home/Home.php');
        exit();
    }
}

// Função para adicionar um "dislike" a um post
function Dislike($ID_user, $ID_post, $FILE){
    if(file_exists($FILE)){
        $posts = json_decode(file_get_contents($FILE), true);
    } else {
        $_SESSION['erro'] = "Erro ao reagir";
        header('Location: ../Home/Home.php');
        exit();
    }
    $found = false;
    foreach($posts as &$post) {
        if ($post['ID_post'] == $ID_post) {
            $post['Dislike']++;
            $post['Dislike_user'][] = $ID_user;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['erro'] = "Erro ao reagir";
        header('Location: ../Home/Home.php');
        exit();
    }
    file_put_contents($FILE, json_encode($posts, JSON_PRETTY_PRINT));
    header('Location: ../Home/Home.php');
    exit();
}   

// Função para adicionar um comentário a um post
function Comentar($ID_user, $ID_post, $FILE, $comentario) {
    if(file_exists($FILE)){
        $posts = json_decode(file_get_contents($FILE), true);
    } else {
        $_SESSION['erro'] = "Erro ao comentar";
        header('Location: ../Home/Home.php');
        exit();
    }
    $found = false;
    foreach($posts as &$post) {
        if ($post['ID_post'] == $ID_post) {
            $post['Comentario']++;
            $post['Comentario_user'][] = $ID_user;
            $post['Comentarios'][] = $comentario;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['erro'] = "Erro ao comentar";
        header('Location: ../Home/Home.php');
        exit();
    }
    file_put_contents($FILE, json_encode($posts, JSON_PRETTY_PRINT));
    header('Location: ../Home/Home.php');
    exit();
}
?>
