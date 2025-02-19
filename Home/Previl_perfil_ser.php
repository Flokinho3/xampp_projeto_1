<?php
include_once "../Server/Porteiro.php";
include_once "../Server/Server.php";

// Verifica se os parâmetros 'ID' 
if(!isset($_GET['ID'])){
    echo "<p>Parâmetros inválidos. ID ou nome não encontrados.</p>";
    exit;
}

// Pega do link o ID do usuário
$ID = $_GET['ID'];

// Pega o nome do usuário
if(isset($_GET['nome'])){
    $nome = $_GET['nome'];
}else{
    $nome = Pesquisar_nome_user($ID);
}

// Pega a imagem do usuário
$img = Pesquisar_img_user($ID);

if($img == 'CSS/IMGS/Perfil_Padrao.jpeg'){
    $img = "../CSS/IMGS/Perfil_Padrao.jpeg";
}else{
    $img = str_replace("Home/", "", $img);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($nome); ?></title>
    <style>
        /* Reset básico para garantir consistência */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilos do corpo */
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(18, 75, 190);
            color: #333;
            padding: 20px;
        }

        /* Estilos para a barra superior */
        .Top_bar {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }

        .Top_bar a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            padding: 5px 10px;
            transition: background-color 0.3s ease;
        }

        .Top_bar a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        /* Estilos do perfil */
        .perfil_ser {
            text-align: center;
            margin-top: 30px;
        }

        .perfil_ser img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #ccc;
            object-fit: cover;
        }

        .perfil_ser h1 {
            margin-top: 15px;
            font-size: 24px;
            color: #333;
        }

        /* Estilos para os posts do perfil */
        .perfil_ser_posts {
            margin-top: 30px;
        }

        .perfil_ser_posts_item {
            background-color: #fff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .perfil_ser_posts_item h3 {
            font-size: 20px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .perfil_ser_posts_item p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .Top_bar {
                padding: 15px;
            }

            .Top_bar a {
                font-size: 16px;
            }

            .perfil_ser img {
                width: 120px;
                height: 120px;
            }

            .perfil_ser h1 {
                font-size: 20px;
            }

            .perfil_ser_posts_item {
                padding: 10px;
            }
        }

    </style>
</head>
<body>
    <div class="Top_bar">
        <a href="Home.php">Home</a>
        <a href="Perfil.php">Perfil</a>
        <a href="../Server/Porteiro.php?action=Sair">Sair</a>
    </div>
    <div class="perfil_ser">
        <img src="<?php echo htmlspecialchars($img); ?>" alt="Perfil">
        <h1><?php echo htmlspecialchars($nome); ?></h1>
    </div>
    <div class="perfil_ser_posts">
        <?php
        // Caminho do diretório dos posts do usuário
        $FILE = "USERS/".$ID."/Puble/";

        // Lista todos os arquivos .json no diretório de posts do usuário
        $files = glob($FILE . "*.json");

        // Verifica se o diretório contém arquivos
        if (count($files) > 0) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    // Lê o conteúdo de cada arquivo de post
                    $post_content = json_decode(file_get_contents($file), true);
                    
                    // Exibe o conteúdo do post
                    echo "<div class='perfil_ser_posts_item'>";
                    echo "<h3>" . htmlspecialchars($post_content['Nome']) . "</h3>";
                    echo "<p>" . nl2br(htmlspecialchars($post_content['Conteudo'])) . "</p>";
                    echo "</div>";
                }
            }
        } else {
            echo "<p>Este usuário não tem posts publicados.</p>";
        }
        ?>
    </div>
</body>
</html>
