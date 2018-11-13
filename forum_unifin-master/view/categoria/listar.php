<?php
require_once './../cabecalho_geral.php';
require_once './../../database/Database.php';
require_once './../../model/Categoria.php';
require_once './../../model/Tema.php';

$admin = $_SESSION['administrador'];
$mensagem = '';
$cat_descricao = isset($_POST['cat_descricao']) ? $_POST['cat_descricao'] : null;
$cat_ativa = isset($_POST['cat_ativa']) ? $_POST['cat_ativa'] : null;

if (isset($_POST['Desativar'])) 
{
    $categoria = new Categoria();
    $codigo_categoria = $_POST['cat_codigo_desativar'];
    $categoria->setCat_codigo($codigo_categoria);
    $desativar = $categoria->desativar();
    if (is_array($desativar)) 
    {
        $mensagem= "Erro: ".$desativar[0].$desativar[2];
    }
}

if (isset($_POST['Ativar'])) 
{
    $categoria = new Categoria();
    $codigo_categoria = $_POST['cat_codigo_ativar'];
    $categoria->setCat_codigo($codigo_categoria);
    $ativar = $categoria->ativar();
    if (is_array($ativar)) 
    {
        $mensagem= "Erro: ".$ativar[0].$ativar[2];
    }
}

if (isset($_POST['Salvar']))
{
    $cat_descricao_inclusao = isset($_POST['cat_descricao_inclusao']) ? $_POST['cat_descricao_inclusao'] : null;
    $categoria = new Categoria();
    $categoria->setCat_descricao($cat_descricao_inclusao);
    $categoria->setCat_ativa('S');
    $incluir = $categoria->incluir();
    if (is_array($incluir))
	{
		$mensagem= "Erro: ".$incluir[0].$incluir[2];
	}
}

if (isset($_POST['Alterar']))
{
    $cat_desc = isset($_POST['cat_descricao_edit']) ? $_POST['cat_descricao_edit'] : null;
    $cat_codigo = isset($_POST['cat_codigo_edit']) ? $_POST['cat_codigo_edit'] : null;
    $categoria = new Categoria();
    $categoria->setCat_codigo($cat_codigo);
    $categoria->setCat_descricao($cat_desc);
    $alterar = $categoria->alterar();
    if (is_array($alterar))
	{
		$mensagem= "Erro: ".$alterar[0].$alterar[2];
	}
}

$limit = 20;
$quantidade = Categoria::contar(null, $cat_descricao, $cat_ativa);
$quantidade = ceil($quantidade / $limit);
$start = isset($_GET['page']) ? $_GET['page'] * $limit - $limit : null;
$resultado= Categoria::listar(null, $cat_descricao, $cat_ativa, $limit, $start);
?>

<link href="./../../assets/css/forum_home.css" rel="stylesheet" media="all">
    <div class="container">
        <div class="pesquisar">
            <form method="POST">
                <div class="form-group row">
                    <div class="col-3">
                        <label for="txtCategoria">Categoria</label>
                        <input type="text" id="txtCategoria" class="form-control" name="cat_descricao" placeholder="Categoria" value='<?= $cat_descricao ?>'>
                    </div>
                    <div class="col-3">
                        <label class="mr-sm-2" for="cat_ativa">Situação</label>
                        <select class="form-control" name="cat_ativa">
                            <option value='' <?php if ($cat_ativa =='') echo 'SELECTED'; ?>></option>
                            <option value='S' <?php if ($cat_ativa =='S') echo 'SELECTED'; ?>>Ativa</option>
                            <option value='N' <?php if ($cat_ativa =='N') echo 'SELECTED'; ?>>Inativa</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="cat_ativa"></label>
                        <input type="submit" value="Pesquisar" name="Pesquisar" class="btn btn-dark form-control btn_pesquisar" />
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive table_comentario w-100">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Categoria</th>          
                        <th>Situação</th>
                        <?php if($admin): ?>            
                            <th>Ação</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($resultado as $chave): ?>
                        <tr>            
                            <td class="wrap_table_content"><?= $chave["cat_descricao"] ?></td>
                            <td><?= $chave['cat_ativa'] == 'S' ? 'Ativa' : 'Inativa' ?></td>
                            <?php if($admin): ?>
                                <td class="text-center">
                                    <?php 
                                        $codigoCategoria = $chave['cat_codigo'];
                                        $cat_desc = $chave['cat_descricao'];             
                                        if ($chave['cat_ativa'] == 'N') 
                                        {
                                            echo "<span data-toggle='modal' data-target='#ativarCategoria' data-cat-id='$codigoCategoria'><a href='javascript:void(0)' class='action_icons' data-toggle='tooltip' title='Ativar'><span class='text-success'><i class='fas fa-check'></i></span></a></span>";  
                                        } 
                                        echo "<span data-toggle='modal' data-target='#editarCategoria' data-cat-id='$codigoCategoria' data-cat-desc='$cat_desc'><a href='javascript:void(0)' class='action_icons' data-toggle='tooltip' title='Editar'><span class='text-primary'><i class='fas fa-pen'></i></span></a></span>";  
                                        if ($chave['cat_ativa'] == 'S')
                                        {
                                            echo "<span data-toggle='modal' data-target='#desativarCategoria' data-cat-id='$codigoCategoria'><a href='javascript:void(0)' class='action_icons' data-toggle='tooltip' title='Desativar'><span class='text-warning'><i class='fas fa-times-circle'></i></span></a></span>";  
                                        }          
                                    ?>
                                </td>
                            <?php endif; ?> 
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
            <div class="col-sm-12">
                <span class="text-danger text-center"><?= $mensagem ?></span>
            </div>
        </div>

                    <!-- MODAIS DA TELA !-->
    </div>
            
    <?php if ($admin): ?>
        <form method="POST" id="formNocaCategoria">
            <div class="container">
                <div class="row">
                    <div class="col-2">
                        <input type="button" value="Nova Categoria" name="Incluir" class="btn btn-dark form-contro btn_novoTema" data-toggle="modal" data-target="#novaCategoria"/>
                    </div>
                </div>
            </div>    
            <div class="modal fade" id="novaCategoria" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Nova Categoria</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="txtDescricao">Descrição</label>
                                        <input type="text" id="txtDescricao" class="form-control" placeholder="Descrição" name="cat_descricao_inclusao" required="required" maxlength="50">                 
                                    </div>
                                </div>
                            </div>   
                            <div class="modal-footer">
                                <button type="button" class="btn btn-dark form-control" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-dark form-control" name="Salvar">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    <?php endif; ?>

    <form method="POST" id="formEditarCategoria">      
        <div class="modal fade" id="editarCategoria" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Alterar Categoria</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="txtDescricao">Descrição</label>
                                <input type="text" id="txtDescricao" class="form-control" placeholder="Descrição" name="cat_descricao_edit" required="required" maxlength="50">  
                                <input type="hidden" id="hdnCatCodigo" name="cat_codigo_edit" />              
                            </div>
                        </div>
                    </div>   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark form-control" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark form-control" name="Alterar">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
     </form>

    <form method="POST" id="formDesativarCategoria">      
        <div class="modal fade" id="desativarCategoria" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Desativar Categoria?</h4>
                        <input type="hidden" name="cat_codigo_desativar" id="hdnCatCodigoDesativar" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark form-control" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-dark form-control" name="Desativar">Sim</button>
                    </div>
                </div>
            </div>
        </div>
     </form>
     
     <form method="POST" id="formAtivarCategoria">      
        <div class="modal fade" id="ativarCategoria" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ativar Categoria?</h4>
                        <input type="hidden" name="cat_codigo_ativar" id="hdnCatCodigoAtivar" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark form-control" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-dark form-control" name="Ativar">Sim</button>
                    </div>
                </div>
            </div>
        </div>
     </form>

<?php require_once './../rodape_geral.php' ?> 

<script src="./../../assets/js/categoria.js"></script>

</body>
</html>



