<?php
session_start();
include "../Server/Alert.php";

// Caso queira debugar a sessão (remova em produção)
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Correção da imagem de perfil padrão
$img = $_SESSION['img'];
if($img == "CSS/IMGS/Perfil_Padrao.jpeg"){
    $img = "../CSS/IMGS/Perfil_Padrao.jpeg";
} else {
    $img = str_replace("Home/", "", $_SESSION['img']);
}

// Carrega as imagens do usuário
$FILES = glob("USERS/".$_SESSION['ID']."/IMGS/*");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil - <?php echo $_SESSION['nome']; ?></title>
  <link rel="stylesheet" href="../CSS/Perfil.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../CSS/Alerta.css?v=<?php echo time(); ?>">
  <script>
    // Variável para controlar o slide atual
    let slideIndex = 1;
    window.onload = function() {
      showSlides(slideIndex);
    };

    // Função para avançar/retroceder os slides
    function plusSlides(n) {
      showSlides(slideIndex += n);
    }

    // Exibe o slide correspondente ao índice
    function showSlides(n) {
      const slides = document.getElementsByClassName("carousel-item");
      if (slides.length === 0) return; // Se não houver slides, não faz nada

      if (n > slides.length) { slideIndex = 1; }
      if (n < 1) { slideIndex = slides.length; }
      
      // Esconde todos os slides
      for (let i = 0; i < slides.length; i++) {
        slides[i].classList.remove("active");
      }
      // Exibe o slide atual
      slides[slideIndex - 1].classList.add("active");
    }
  </script>
  <?php
    // Exibe alertas se existirem
    if(isset($_SESSION['sucesso'])){
      echo "<script>alert('".$_SESSION['sucesso']."');</script>";
      unset($_SESSION['sucesso']);
    }
    if(isset($_SESSION['erro'])){
      echo "<script>alert('".$_SESSION['erro']."');</script>";
      unset($_SESSION['erro']);
    }
  ?>
  <script>
    // Função para copiar texto (já presente no seu código)
    function copiarTexto(id) {
      var texto = document.getElementById(id).innerText;
      var conteudo = texto.split(": ")[1];
      navigator.clipboard.writeText(conteudo).then(() => {
          alert("Copiado com sucesso");
      });
    }
  </script>
</head>
<body>
  <!-- From Uiverse.io by Spacious74 --> 
  <div class="radio-inputs">
    <label class="radio">
      <input type="radio" name="radio" checked="" />
      <a href="Home.php">
        <span class="name">Home</span>
      </a>
    </label>
    <label class="radio">
      <input type="radio" name="radio" />
      <a href="Perfil.php">
        <span class="name">Perfil</span>
      </a>
    </label>

    <label class="radio">
      <input type="radio" name="radio" />
      <a href="../Server/Porteiro.php?action=Sair">
        <span class="name">Sair</span>
      </a>
    </label>

    <label class="radio">
      <input type="radio" name="radio" />
      <a href="../Server/Porteiro.php?action=Atualizar_sessao">
        <span class="name">Atualizar sessão</span>
      </a>
    </label>
  </div>

  <div class="container">
    <h1>Perfil</h1>
    <div class="Perfil_info">
      <h2>Informações Pessoais</h2>
      <div class="IMG_perfil">
        <img src="<?php echo $img; ?>" alt="Perfil">
      </div>
      <div class="Perfil_info_item">
        <p id="nome">Nome: <?php echo $_SESSION['nome']; ?></p>
        <button onclick="copiarTexto('nome')">
          <span class="text">Copiar</span>
        </button>
      </div>
      <div class="Perfil_info_item">
        <p id="email">Email: <?php echo $_SESSION['email']; ?></p>
        <button onclick="copiarTexto('email')">
          <span class="text">Copiar</span>
        </button>
      </div>
      <div class="Perfil_info_item">
        <p id="data_naci">Data de Nascimento: <?php echo $_SESSION['data_naci']; ?></p>
        <button onclick="copiarTexto('data_naci')">
          <span class="text">Copiar</span>
        </button>
      </div>
      <div class="Perfil_info_item">
        <p id="niki">Niki: <?php echo $_SESSION['niki']; ?></p>
        <button onclick="copiarTexto('niki')">
          <span class="text">Copiar</span>
        </button>
      </div>
      <div class="Perfil_info_item">
        <p id="ID">ID: <?php echo $_SESSION['ID']; ?></p>
        <button onclick="copiarTexto('ID')">
          <span class="text">Copiar</span>
        </button>
      </div>
    </div>
    <div class="Img_user_escolha">
      <h2>Escolha uma imagem de perfil</h2>
      <div class="Img_user_escolha_item">
        <p>Perfil atual</p>
        <img src="<?php echo $img; ?>" alt="Perfil">
        </div>
        <!-- Carousel de imagens -->
        <div class="carousel">
            <div class="carousel-inner">
            <?php
            if (!empty($FILES)) {
                $i = 0;
                foreach($FILES as $file) {
                    // Define a classe 'active' para o primeiro item
                    $active = ($i === 0) ? 'active' : '';
                    echo "<div class='carousel-item $active'>";
                    echo "<img src='$file' alt='Imagem de perfil'>";
                    echo "<a href='../Server/Porteiro.php?action=Atualizar_img&img=$file'>Atualizar</a>";
                    echo "</div>";
                    $i++;
                }
            } else {
                echo "<p>Nenhuma imagem disponível. Adicione uma imagem para seu perfil.</p>";
            }
            ?>
            </div>
            <!-- Botões de navegação -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
        <div class="Img_user_escolha_item">
          <form action="../Server/Porteiro.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="action" value="Upload_IMG">
              <input type="file" name="file" id="file">
              <button type="submit">Upload</button>
          </form>

        </div>
    </div>
  </div>
</body>
</html>
