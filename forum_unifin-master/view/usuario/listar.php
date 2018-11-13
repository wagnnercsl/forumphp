<?php	
require_once './../cabecalho_geral.php';
require_once './../../model/Usuario.php';
require_once './../../database/Database.php';

if (!$_SESSION['administrador'])
{
	header('location: ./../forum/home.php?page=1');
}

if (isset($_POST['Incluir']))
{
	header('Location: ./incluir.php');
	exit();
}

if (isset($_POST['Ativar']))
{
	$usuario = new Usuario();
	$usuario->setUsu_login($_POST['usu_login_ativar']);
	$ativar = $usuario->ativar();

	if (is_array($ativar))
	{
		$mensagem= "Erro: ".$ativar[0].$ativar[2];
	}
}

if (isset($_POST['Desativar']))
{
	$usuario = new Usuario();
	$usuario->setUsu_login($_POST['usu_login_desativar']);
	$desativar = $usuario->desativar();

	if (is_array($desativar))
	{
		$mensagem= "Erro: ".$desativar[0].$desativar[2];
	}
}

$ativo = array('S' => 'Ativo', 'P' => 'Pendente', 'N' => 'Inativo');

$limit = 10;
$usu_nome= isset($_POST['usu_nome']) ? $_POST['usu_nome'] : null;
$usu_ativo= isset($_POST['usu_ativo']) ? $_POST['usu_ativo'] : null;
$quantidade = Usuario::contar();
$quantidade = ceil($quantidade / $limit);
$start = isset($_GET['page']) ? $_GET['page'] * $limit - $limit : null;
$resultado= Usuario::listar(null, $usu_nome, $usu_ativo, $limit, $start);
?>

<link href="./../../assets/css/usuario.css" rel="stylesheet" media="all">
	<div class="container pesquisar">
		<form method='POST'>
			<input type="text" style="display:none" id="codigoTemaDesativar" name="codigoTemaDesativar" />
            <div class="form-group row">
                <div class="col-3">
                    <label for="txtNome">Nome do usuário</label>
                    <input type="text" id="txtNome" class="form-control" name="usu_nome" placeholder="Nome" value='<?= $usu_nome ?>'>
                </div>
                <div class="col-3">
                    <label class="mr-sm-2" for="usu_ativo">Situação</label>
                    <select class="form-control" name="usu_ativo">
						<option value=''  <?php if ($usu_ativo =='') echo 'SELECTED'; ?>>        </option>
						<option value='S' <?php if ($usu_ativo=='S') echo 'SELECTED'; ?>>Ativo   </option>
						<option value='P' <?php if ($usu_ativo=='P') echo 'SELECTED'; ?>>Pendente</option>
            			<option value='N' <?php if ($usu_ativo=='N') echo 'SELECTED'; ?>>Inativo </option>
                    </select>
                </div>
                <div class="col-3">
                    <label for="usu_ativo"></label>
                    <input type="submit" value="Pesquisar" name="Pesquisar" class="btn btn-dark form-control btn_pesquisar" />
                </div>
            </div>
		</form>

		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover">
				<thead>
				<tr>
					<th>Login</th>
					<th>Nome</th>        		
					<th>E-mail</th>        		        		     		        		      		        		
					<th>Situação</th>        		
					<th>Ação</th>        		
				</tr>
				</thead>
				
				<tbody>
				<?php foreach($resultado as $chave): ?>
				<tr>
					<td><?= $chave["usu_login"] ?></td>
					<td><?= $chave["usu_nome"] ?></td>
					<td><?= $chave["usu_email"] ?></td>
					<td><?= $ativo[$chave['usu_ativo']] ?: 'Indefinido' ?></td>
					<td class="text-center">
						<?php if ($chave['usu_ativo'] != 'S'): ?>
							<span data-toggle="modal" data-target="#ativarUsuario" data-usu-login="<?= $chave['usu_login'] ?>"><a href="javascript:void(0)" data-toggle="tooltip" title="Aprovar" class="action_icons"><span class="text-success"><i class="fas fa-check"></i></span></a></span>
						<?php endif; ?>
						<a href='./alterar.php?login=<?= $chave["usu_login"] ?>' data-toggle="tooltip" title="Alterar" class="action_icons"><i class="fas fa-user-edit"></i></a>
						<?php if ($chave['usu_ativo'] == 'S'): ?>
							<span data-toggle="modal" data-target="#desativarUsuario" data-usu-login="<?= $chave['usu_login'] ?>"><a href="javascript:void(0)" data-toggle="tooltip" title="Desativar" class="action_icons"><span class="text-warning"><i class="fas fa-times-circle"></i></span></a></span>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				</tbody>	
			</table>
		</div>

		<div class="row">      
			<div class="col-sm-6">
				<ul class="pagination">
				<?php for($i = 1; $i <= $quantidade; $i++) { 
					echo "<li><a href='?page=$i' class='btn btn-dark btn_pg'>$i</a></li>";
				} ?>
				</ul>
			</div>
		</div>

		<div class="row">
			<form method="POST">
				<div class="col-2">
					<input type="submit" value="Novo Usuário" name="Incluir" class="btn btn-dark form-contro btn_novoUsuario"/>
				</div>
			</form>
        </div>
			       

	</div>

	<form method="POST" id="formDesativarUsuario">      
        <div class="modal fade" id="desativarUsuario" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Desativar Usuário?</h4>
                        <input type="hidden" name="usu_login_desativar" id="hdnLoginUsuarioDesativar" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark form-control" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-dark form-control" name="Desativar">Sim</button>
                    </div>
                </div>
            </div>
        </div>
     </form>

	 <form method="POST" id="formAtivarUsuario">      
        <div class="modal fade" id="ativarUsuario" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ativar Usuário?</h4>
                        <input type="hidden" name="usu_login_ativar" id="hdnLoginUsuarioAtivar" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark form-control" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-dark form-control" name="Ativar">Sim</button>
                    </div>
                </div>
            </div>
        </div>
     </form>

<?php require_once './../rodape_geral.php'; ?>

<script src="./../../assets/js/usuario_listar.js"></script>

</body>
</html>
