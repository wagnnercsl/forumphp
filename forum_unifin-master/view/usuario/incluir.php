<?php	
require_once './../../model/Usuario.php';
require_once './../../database/Database.php';

session_start();
if (isset($_SESSION['usuario']))
{
	if(!$_SESSION['administrador'])
	{
		header("Location: ./../forum/home.php?page=1");
		exit();
	}
	require_once './../cabecalho_geral.php';
	$logado = true;
}
else
{
	require_once './../cabecalho_geral_semmenu.php';
	$logado = false;
}

$mensagem= "";
$path = './../../assets/img/user_profile_image.png';
$incluir = true;

$usu_login = isset($_POST['usu_login']) ? $_POST['usu_login'] : null;
$usu_nome = isset($_POST['usu_nome']) ? $_POST['usu_nome'] : null;
$usu_email = isset($_POST['usu_email']) ? $_POST['usu_email'] : null;
$usu_senha = isset($_POST['usu_senha']) ? $_POST['usu_senha'] : null;
$usu_senha_conf = isset($_POST['usu_senha_conf']) ? $_POST['usu_senha_conf'] : null;
$usu_administrador = isset($_POST['usu_administrador']) ? $_POST['usu_administrador'] : 'N';
$usu_ativo = $logado ? 'S' : 'P';

if (isset($_POST['Salvar'])) 
{
	if ($usu_senha != $usu_senha_conf) 
	{
        $mensagem= "Erro: a senha não está igual a confirmação da senha.";
	} 
	else 
	{ 
		if ($_FILES['usu_avatar']['name'] != '')
		{
			if (preg_match("!image!", $_FILES['usu_avatar']['type']))
			{
				$path = './../../assets/img/' . $usu_login . "_" . $_FILES['usu_avatar']['name'];

				if (!file_exists($path))
				{
					if (!copy($_FILES['usu_avatar']['tmp_name'], $path))
					{
						$mensagem = "Erro ao fazer upload da imagem! Tende novamente!";
						$incluir = false;
					}
				}
			}
			else
			{
				$mensagem = "Formato de arquivo errado. Você deve selecionar uma imagem como avatar!";
				$incluir = false;
			}
		}   

		if ($incluir)
		{
			$usuario = new Usuario();
			$usuario->setUsu_login($usu_login);
			$usuario->setUsu_nome($usu_nome);
			$usuario->setUsu_email($usu_email);
			$usuario->setUsu_senha($usu_senha);
			$usuario->setUsu_administrador($usu_administrador);
			$usuario->setUsu_ativo($usu_ativo);
			$usuario->setUsu_avatar($path);
			$resultado = $usuario->incluir();
		
			if (is_array($resultado))
			{
				$mensagem= "Erro: ".$resultado[0].$resultado[2];
			}
			else 
			{
				if ($logado)
				{
					
					echo "<script>
					alert('Inclusão realizada com sucesso!');
					window.location.href='javascript:history.go(-3)';
					</script>";
					exit();
				}
				else
				{
					echo "<script>
					alert('Inclusão realizada com sucesso! Aguarde a aprovação.');
					window.location.href='./../login/login.php';
					</script>";
					exit();
				}
			}
		}
    }
}

?>

 	<div class="container">
        <h4 style="margin-top: 30px;" class="text-center">Preencha os campos para realizar a inclusão </h4>
		<span class="text-danger">* Campos obrigatórios</span>
        <div class="row">
            <div class="col-md-12 col-sd-12 vertical-center">
                <div class="jumbotron">
                    <form method="POST" enctype="multipart/form-data">

						<div class="form-group row">

							<div class="col">

								<label for="txtNome">Nome<span class="text-danger">*</span></label>
								<input type="text" id="txtNome" class="form-control" name="usu_nome" placeholder="Nome" required="required" value='<?= $usu_nome ?>'>

							</div>

						</div>

                        <div class="form-group row">

							<div class="col-6">

								<label for="txtUsuario">Usuário<span class="text-danger">*</span></label>
								<input type="text" id="txtUsuario" class="form-control" name="usu_login" placeholder="Usuário" required="required" maxlength="11" value='<?= $usu_login ?>'> 

							</div>

							<div class="col-6">

								<label for="txtEmail">E-mail<span class="text-danger">*</span></label>
								<input type="email" id="txtEmail" class="form-control" name="usu_email" placeholder="E-mail" required="required" value='<?= $usu_email ?>'>

							</div>

						</div>

						 <div class="form-group row">

							<div class="col-6">

                                <label for="txtSenha">Senha<span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="txtSenha" name="usu_senha" placeholder="Senha" required="required">

                            </div>

                            <div class="col-6">

                                <label for="txtSenhaConf">Confirme sua senha<span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="txtSenhaConf" name="usu_senha_conf" placeholder="Confirme a senha" required="required">

                            </div>

						</div>

						<div class="form-group row">

							<div class="col-md-6">

								<label for="txtAvatar">Foto de perfil</label>
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Selecione uma foto para o perfil" id="txtAvatar" readonly />
									<span class="input-group-btn">
										<input type="button" value="Procurar" class="btn btn-dark form-control" id="btnFotoPerfil" />
									</span>
									<input type="file" id="fotoPerfil" style="display:none" accept="image/*" name="usu_avatar"/>
								</div>

							</div>

							<div class="col-md-6">
								<span class="newComer text-danger text-center"><?= $mensagem ?></span>
							</div>

						</div>

						<?php if ($logado): ?>
	
							<div class="form-group row">

								<div class="col-md-2">

									<label for="rbUsuAdminSim">Administrador</label>

								</div>

								<div class="col-md-4">

									<div class="custom-control custom-radio custom-control-inline">

										<input type="radio" id="rbUsuAdminSim" name="usu_administrador" class="custom-control-input" value="S">
										<label class="custom-control-label" for="rbUsuAdminSim">Sim</label>

									</div>

									<div class="custom-control custom-radio custom-control-inline">

										<input type="radio" id="rbUsuAdminNao" name="usu_administrador" class="custom-control-input" value="N" checked="checked">
										<label class="custom-control-label" for="rbUsuAdminNao">Não</label>

									</div>

								</div>

							</div>

						<?php endif;?>

						<div class="row">
                            <div class="col-md-12 col-sd-12">
                                <hr />
                            </div>
                        </div>
						
						<div class="row justify-content-center">
							<div class="col-4">
								<input type="button" onclick="javascript:location.href='<?php echo $_SERVER['HTTP_REFERER']; ?>'" class="btn btn-dark form-control" value="Cancelar" /> 
							</div>
                            <div class="col-4">
                                <input type="submit" value="Salvar" name="Salvar" class="btn btn-dark form-control" />
                            </div>
                        </div>

					</form>
				</div>
			</div>
		</div>
	</div>

<?php require_once './../rodape_geral.php'; ?>

<script src="./../../assets/js/usuario.js"></script>

</body>
</html>
