<?php
function Alertar_Erro($mensagem){
    $_SESSION['erro'] = $mensagem;
}

function Alertar_Sucesso($mensagem){
    $_SESSION['sucesso'] = $mensagem;
}

function Limpar_Alertas(){
    unset($_SESSION['erro']);
    unset($_SESSION['sucesso']);
}

function Exibir_Alertas(){
    if(isset($_SESSION['erro']) && isset($_SESSION['sucesso'])) {
        echo "<div class='alerta erro'>".$_SESSION['erro']."</div>";
        echo "<script>
            setTimeout(function() {
                document.querySelector('.alerta.sucesso').style.display = 'block';
            }, 3000);
            setTimeout(function() {
                document.querySelector('.alerta.erro').classList.add('sair');
                document.querySelector('.alerta.sucesso').classList.add('sair');
            }, 6000);
        </script>";
        echo "<div class='alerta sucesso' style='display:none'>".$_SESSION['sucesso']."</div>";
        Limpar_Alertas();
    } else {
        if(isset($_SESSION['erro'])){
            echo "<div class='alerta erro'>".$_SESSION['erro']."</div>";
            echo "<script>
                setTimeout(function() {
                    document.querySelector('.alerta.erro').classList.add('sair');
                }, 3000);
            </script>";
            Limpar_Alertas();
        }
        if(isset($_SESSION['sucesso'])){
            echo "<div class='alerta sucesso'>".$_SESSION['sucesso']."</div>";
            echo "<script>
                setTimeout(function() {
                    document.querySelector('.alerta.sucesso').classList.add('sair');
                }, 3000);
            </script>";
            Limpar_Alertas();
        }
    }
}



?>