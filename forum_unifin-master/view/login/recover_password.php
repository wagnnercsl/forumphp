<?php	
	require_once './../cabecalho_geral_semmenu.php';
	require_once './../../model/Usuario.php';
    require_once './../../database/Database.php';
    require_once './../../model/Email.php';

    $usu_email = isset($_POST['email']) ? $_POST['email'] : null;
    $usu_nova_senha = sprintf("%08d", mt_rand(1, 999999));
	$mensagem = '';

	if(isset($_POST['Enviar']))
	{
        $usuario = new Usuario();
        $usuario->setUsu_email($usu_email);
        $usuario->setUsu_senha($usu_nova_senha);
        $usuario->recuperarSenha();

        if (is_array($resultado))
        {
            $mensagem= "Erro: ".$resultado[0].$resultado[2];
        }
        else
        {
            $email = new Email();
            $retorno = $email->emailRecuperacaoSenha($usu_email, $usu_nova_senha);
            if (!$retorno)
            {
                $mensagem = "Erro ao enviar o email. Tente novamente!";
            }
            else
            {
                echo "<script>
                alert('E-mail contendo as informações da senha enviado!');
                window.location.href = './../login/login.php';
                </script>";
                exit();
            }
        }
	}
?>

<div class="container">

    <div class="row">

        <div class="col-md-12 col-sd-12 vertical-center row">

            <div class="col-md-3"></div>

            <div class="col-md-6" style="margin-top: 4em; padding-bottom: 1.5em; background-color: #50454545; border-radius: 10px;">
                <header>
                    <h5 class="title">Informe o seu e-mail para recuperar a senha</h5>
                </header>
                <form style="padding-left: 3em; padding-right: 3em" method="POST">
                    <div class="form-group row">   

                        <div class="col">

                            <label for="txtEmail">E-mail</label>
                            <input type="email" id="txtEmail" class="form-control" name="email" placeholder="E-mail" required="required">
                            <span class="text-danger"><?= $mensagem ?></span>
                        </div>

                    </div>

                    <div class="row justify-content-center">
                        <div class="col-4">
                            <input type="button" onclick='javascript:location.href="./../login/login.php";' class="btn btn-dark form-control" value="Cancelar" /> 
                        </div>
                        <div class="col-4">
                            <input type="submit" value="Enviar" name="Enviar" class="btn btn-dark form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once './../rodape_geral.php'; ?>

</body>
</html>