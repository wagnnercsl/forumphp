<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['usuario']))
    {
        //Pega o horário atual
        $time = time();
        //Seta o tempo de timeout em minutos
        $timeout = 20;

        //Pega o tempo inativo em segundos
        $secondsInactive = $time - $_SESSION['ultimaAtualizacao'];

        //Converte o tempo de timeout para segundos
        $timeoutSeconds = $timeout * 60; 

        //Se o tempo inativo for maior ou igual ao tempo estipulado, encerra a sessão e volta para a tela de login
        //Senão, atualiza o tempo de última atividade
        if ($secondsInactive >= $timeoutSeconds)
        {
            session_unset();
            session_destroy();
            echo "<script>
            alert('Sessão expirada! Logue novamente.');
            window.location.href = './../login/login.php';
            </script>";
            exit();
        }
        else
        {
            $_SESSION['ultimaAtualizacao'] = $time;
        }

    }
    else
    {   
        echo "<script>
            alert('É necessário realizar o login antes de acessar o site.');
            window.location.href = './../login/login.php';
            </script>";
        exit();
    }
?>