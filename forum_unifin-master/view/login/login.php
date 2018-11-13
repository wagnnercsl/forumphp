<?php	
	require_once './../cabecalho_geral_semmenu.php';
	require_once './../../model/Usuario.php';
	require_once './../../database/Database.php';

	session_start();
	
	if (isset($_SESSION['usuario']))
	{
		header("Location: ./../forum/home.php?page=1");
		exit();
	}

	$usu_login = isset($_POST['usuario']) ? $_POST['usuario'] : null;
	$usu_senha = isset($_POST['senha']) ? $_POST['senha'] : null;
	$mensagem = '';

	if(isset($_POST['Entrar']))
	{
		$resultado= Usuario::login($usu_login, $usu_senha);
		if(sizeof($resultado) == 0)
		{
			$mensagem = "Usuário ou senha inválidos.";
		}
		else
		{
			if($resultado[0]['usu_ativo'] == 'P')
			{
				$mensagem = "Usuário pendente. Aguarde a aprovação dos administradores!";
			}
			else if($resultado[0]['usu_ativo'] == 'N')
			{
				$mensagem = "Usuário desativado!";
			}
			else
			{
				$_SESSION['usuario'] = $usu_login;
				$_SESSION['ultimaAtualizacao'] = time();

				if ($resultado[0]['usu_administrador'] == 'S')
				{
					$_SESSION['administrador'] = true;
				}
				else
				{
					$_SESSION['administrador'] = false;
				}
				header("Location: ./../forum/home.php?page=1");
				exit();
			}
		}
	}
?>

<link href="../../assets/css/login.css" rel="stylesheet" media="all">

<div class="container">

	<div class="row">

		<div class="col-md-12 col-sd-12 vertical-center row">

			<div class="col-md-3"></div>

			<div class="col-md-6" style="margin-top: 4em; padding-bottom: 1.5em; background-color: #50454545; border-radius: 10px;">

                <header>
                    <h1 class="logotipo"><img class="img-responsive" src="./../../assets/img/forum-icon.png"></h1>
                    <h3 class="title">Entre com seu <b>usuário</b> e <b>senha</b></h2>
                </header>

				<form style="padding-left: 3em; padding-right: 3em" method="POST">

					<div class="form-group row">   

						<div class="col">

							<label for="txtUsuario">Usuário</label>
							<input type="text" id="txtUsuario" class="form-control" name="usuario" placeholder="Usuário" required="required">

						</div>

					</div>

					<div class="form-group row">

						<div class="col">

							<label for="txtSenha">Senha</label>
							<input type="password" id="txtSenha" name="senha" class="form-control" placeholder="Senha" required="required">
							<span class="newComer text-danger text-center"><?= $mensagem ?></span>
						</div>

					</div>

					<div class="form-group row">

						<div class="col-6">

							<button class="recoverPwd" type="button" name="RecoverPwd" id="btnRecoverPwd" onclick='javascript:location.href="./recover_password.php";'>Esqueceu sua senha?</button>
						
						</div>

						<div class="col-6">

							<input type="submit" name="Entrar" value="Entrar" id="btnLogin" class="btn btn-dark form-control" />

						</div>

					</div>

                    <div class="form-group row">

                        <div class="col-12">

                            <span class="newComer">Não possui um cadastro ainda? <a href="./../usuario/incluir.php">Crie sua conta agora</a></span>

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