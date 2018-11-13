<?php	
	require_once './../cabecalho_geral.php';
	require_once './../../model/Usuario.php';
    require_once './../../database/Database.php';
    require_once './../../model/Email.php';
    require_once './../../model/Comentario.php';
    require_once './../../model/Tema.php';
    require_once './../../model/Categoria.php';

    $motivo = isset($_POST['motivo']) ? $_POST['motivo'] : null;
    $pagina = "javascript:history.go(-1)";
	$mensagem = '';

	if(isset($_POST['Enviar']))
	{
        $email = new Email();
        $ret = $email->denunciarComentario($_GET['comentario'], $_SESSION['usuario'], $motivo);
        if(!$ret)
        {
            $mensagem = "Erro ao enviar o email. Tente novamente!";
        }
        else
        {
            echo "<script>
            alert('E-mail de denúncia enviado aos administradores!');
            window.location.href='javascript:history.go(-2)';
            </script>";
            exit();
        }
	}
?>

<div class="container">

    <div class="row">

        <div class="col-md-12 col-sd-12 vertical-center row">

            <div class="col-md-3"></div>

            <div class="col-md-6" style="margin-top: 4em; padding-bottom: 1.5em; background-color: #50454545; border-radius: 10px;">
                <header>
                    <h5 class="title">Informe o motivo da denúncia</h5>
                </header>
                <form style="padding-left: 3em; padding-right: 3em" method="POST">
                    <div class="form-group row">   

                        <div class="col">
                            <label for="txtMotivo">Motivo</label>
                            <textarea class="form-control" rows="3" maxlength="2000" id="txtMotivo" name="motivo" required="required"></textarea>
                            <span class="text-danger"><?= $mensagem ?></span>
                        </div>

                    </div>

                    <div class="row justify-content-center">
                        <div class="col-4">
                            <input type="button" onclick="javascript:location.href='<?php echo $pagina; ?>'" class="btn btn-dark form-control" value="Cancelar" id="btnCancelar"/> 
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