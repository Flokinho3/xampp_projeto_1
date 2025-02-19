<?php
session_start();

if(!isset($_SESSION['email'])){
    header('Location: ../Porteiro/Login.php');
}
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/

if($_SESSION['img'] == 'CSS/IMGS/Perfil_Padrao.jpeg'){
    $img = "../CSS/IMGS/Perfil_Padrao.jpeg";
}else{
    //remove o ../Home/
    $img = str_replace("Home/", "", $_SESSION['img']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - <?php echo $_SESSION['nome']; ?></title>
    <link rel="stylesheet" href="../CSS/Home.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="Top_bar"> 
        <div class="Botoes">
            <div class="Perfil_user">
                <img src="<?php echo $img; ?>" alt="Perfil">
                <p><?php echo $_SESSION['nome']; ?></p>
                <a href="Perfil.php">Perfil</a>
                <button class="Btn" onclick="window.location.href='../Server/Porteiro.php?action=Sair'">
                    <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
                    <div class="text">Logout</div>
                </button>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="header">
            <!-- From Uiverse.io by Yaseen549 --> 
            <input placeholder="Em que você está pensando?" class="input" name="text" type="text">
            <!-- From Uiverse.io by adamgiebl --> 
            <button>
            <div class="svg-wrapper-1" onclick="enviarPost()">
                <div class="svg-wrapper">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                >
                    <path fill="none" d="M0 0h24v24H0z"></path>
                    <path
                    fill="currentColor"
                    d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"
                    ></path>
                </svg>
                </div>
            </div>
            <script>
                function enviarPost() {
                    const input = document.querySelector('.input');
                    const conteudo = input.value.trim();
                    
                    if (conteudo === '') {
                        alert('Por favor, digite algo antes de enviar!');
                        return;
                    }

                    // Criando o objeto com os dados do post
                    const data = new FormData();
                    data.append('action', 'Novo_post');
                    data.append('Conteudo', conteudo);
                    data.append('ID', <?php echo $_SESSION['ID']; ?>);

                    // Usando fetch para enviar os dados sem recarregar a página
                    fetch('../Server/Porteiro.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.text())
                    .then(responseText => {
                        console.log("Resposta do servidor:", responseText);
                        
                        // Limpa o input após enviar
                        input.value = '';
                        
                        // Recarrega a página para mostrar o novo post
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao enviar o post. Tente novamente.');
                    });
                }
            </script>

        </div>
        <div class="posts">
            <?php
            $FILE = "../Home/USERS/Atualizacoes.json";

            // Verifica se o arquivo existe
            if (file_exists($FILE)) {
                // Lê o conteúdo do arquivo
                $posts = json_decode(file_get_contents($FILE), true);
            } else {
                echo "<div class='Erro'><p>Nenhum post encontrado</p></div>";
                exit();
            }
                // Lê o conteúdo do arquivo
                $posts = json_decode(file_get_contents($FILE), true);

                // Itera sobre cada post
                foreach ($posts as $post) {
                    $Post_user = $post['ID_user'];
                    $Post_id = $post['ID_post'];
                    $Post_file = $post['File'];

                    // Carrega o conteúdo do arquivo do post
                if (file_exists($Post_file)) {
                    $Post_content = json_decode(file_get_contents($Post_file), true);

                    // Verificar se o JSON foi decodificado corretamente
                    if ($Post_content === null) {
                        echo "<p>Erro ao carregar o post. Dados corrompidos.</p>";
                        exit();
                    }

                    include_once "../Server/Server.php";
                    // Exibe o post
                    echo "<div class='post'>";
                    echo "<div class='post_img'>";
                    
                    // Pega o ID_user do post e pesquisa a imagem do usuário
                    $img = Pesquisar_img_user($Post_user);
                    
                    // Verifica se o caminho da imagem existe
                    if (file_exists($img)) {
                        echo "<a href='Previl_perfil_ser.php?ID=".$Post_user."&nome=".$Post_content['Nome']."'><img src='".$img."' alt='Post Image'></a>";
                    } else {
                        echo "<img src='../CSS/IMGS/Perfil_Padrao.jpeg' alt='Post Image'>";
                    }
                    echo "</div>";
                    
                    echo "<div class='post_content'>";
                    echo "<h1><a href='Previl_perfil_ser.php?ID=".$Post_user."&nome=".$Post_content['Nome']."'>" . $Post_content['Nome'] . "</a></h1>";
                    echo "<p>" . $Post_content['Conteudo'] . "</p>";
                    
                    // Botões de interação
                    echo "<div class='post_btns_Like'>";
                    echo "<button class='Btn' data-post-id='" . $Post_id . "' data-user-id='" . $Post_user . "' data-post-file='" . $Post_file . "' onclick='likePost(" . $Post_id . ", " . $Post_user . ", \"" . $Post_file . "\")'>Like</button>";
                    echo "<button class='Btn' data-post-id='" . $Post_id . "' data-user-id='" . $Post_user . "' data-post-file='" . $Post_file . "' onclick='dislikePost(" . $Post_id . ", " . $Post_user . ", \"" . $Post_file . "\")'>Dislike</button>";
                    echo "<button class='Btn' data-post-id='" . $Post_id . "' data-user-id='" . $Post_user . "' data-post-file='" . $Post_file . "' onclick='toggleComentario(this)'>Comentar</button>";
                    
                    echo "<div class='comentario-container' style='display:none'>";
                    echo "<input type='text' class='comentario-input' placeholder='Digite seu comentário...'>";
                    echo "</div>";
                    
                    echo "<script>
                        function toggleComentario(btn) {
                            const container = btn.nextElementSibling;
                            const input = container.querySelector('.comentario-input');
                            
                            if (container.style.display === 'none') {
                                container.style.display = 'block';
                                btn.textContent = 'Enviar';
                            } else {
                                // Envia o comentário para o porteiro (cuidado com a codificação da URL)
                                const comentario = encodeURIComponent(input.value);  // Encode para evitar problemas de URL
                                window.location.href = '../Server/Porteiro.php?action=comentar&post_id=" . $Post_id . "&id_user=" . $Post_user . "&file=" . $Post_file . "&comentario=' + comentario;
                            }
                        }
                    </script>";
                    
                    echo "</div>";
                    echo "</div>";
                } else {
                    // Caso o arquivo não exista, exibe um erro
                    echo "<div class='Erro'>";
                    echo "<p style='color: #fff; font-weight: bold; text-align: center; padding: 10px; background-color: #ff0000; border: 1px solid #cc0000; border-radius: 4px;'>Erro: Post foi apagado! Consulte o dono do post!</p>";
                    echo "<a href='Previl_perfil_ser.php?ID=".$Post_user."' style='color: #fff; font-weight: bold; text-align: center; padding: 10px; background-color: #0066cc; border: 1px solid #0052a3; border-radius: 4px; text-decoration: none; display: block; width: 100px; margin: 10px auto;'>Ver perfil</a>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html> 
