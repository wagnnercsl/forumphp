<?php
require_once './../cabecalho_geral.php';
require_once './../../model/Tema.php';
require_once './../../database/Database.php';
require_once './../../model/Categoria.php';

$admin = $_SESSION['administrador'];
$mensagem = '';
$tem_descricao = isset($_POST['tem_descricao']) ? $_POST['tem_descricao'] : null;
$tem_ativo = isset($_POST['tem_ativo']) ? $_POST['tem_ativo'] : null;

if (isset($_POST['Desativar'])) 
{
    $tema = new Tema();
    $tema->setTem_codigo($_POST['tem_codigo_desativar']);
    $desativar = $tema->desativar();
    if (is_array($desativar)) 
    {
        $mensagem= "Erro: ".$desativar[0].$desativar[2];
    }
}

if (isset($_POST['Ativar'])) 
{
    $tema = new Tema();
    $tema->setTem_codigo($_POST['tem_codigo_ativar']);
    $ativar = $tema->ativar();
    if (is_array($ativar)) 
    {
        $mensagem= "Erro: ".$ativar[0].$ativar[2];
    }
}

if (isset($_POST['Salvar']))
{
    $cat_codigo = isset($_POST['cat_codigo']) ? $_POST['cat_codigo'] : null;
    $tem_descricao_inclusao = isset($_POST['tem_descricao_inclusao']) ? $_POST['tem_descricao_inclusao'] : null;
    $tema = new Tema();
    $tema->setCat_codigo($cat_codigo);
    $tema->setTem_descricao($tem_descricao_inclusao);
    $tema->setTem_ativo('S');
    $tema->setTem_criacao(date('Y-m-d', time()));
    $incluir = $tema->incluir();
    if (is_array($incluir))
	{
		$mensagem= "Erro: ".$incluir[0].$incluir[2];
	}
}

if (isset($_POST['Alterar']))
{
    $tem_codigo = isset($_POST['tem_codigo_edit']) ? $_POST['tem_codigo_edit'] : null;
    $tem_desc = isset($_POST['tem_descricao_edit']) ? $_POST['tem_descricao_edit'] : null;
    $cat_codigo = isset($_POST['cat_codigo_edit']) ? $_POST['cat_codigo_edit'] : null;
    $tema = new Tema();
    $tema->setCat_codigo($cat_codigo);
    $tema->setTem_codigo($tem_codigo);
    $tema->setTem_descricao($tem_desc);
    $alterar = $tema->alterar();
    if (is_array($alterar))
	{
		$mensagem= "Erro: ".$alterar[0].$alterar[2];
	}
}

$codigoTema = '';

$categorias = Categoria::listar(null, null, 'S');
$limit = 10;
$quantidade = Tema::contar(null, $tem_descricao, $tem_ativo);
$quantidade = ceil($quantidade / $limit);
$start = isset($_GET['page']) ? $_GET['page'] * $limit - $limit : null;
$resultado= Tema::listar(null, $tem_descricao, $tem_ativo, $limit, $start);
?>

<link href="./../../assets/css/forum_home.css" rel="stylesheet" media="all">
    <div class="container">
        <div class="pesquisar">
            <form method="POST" id="formDesativar">
                <input type="text" style="display:none" id="codigoTemaDesativar" name="codigoTemaDesativar" />
                <div class="form-group row">
                    <div class="col-3">
                        <label for="txtNome">Tema</label>
                        <input type="text" id="txtTema" class="form-control" name="tem_descricao" placeholder="Tema" value='<?= $tem_descricao ?>'>
                    </div>
                    <div class="col-3">
                        <label class="mr-sm-2" for="tem_ativo">Situação</label>
                        <select class="form-control" name="tem_ativo">
                            <option value='' <?php if ($tem_ativo =='') echo 'SELECTED'; ?>></option>
                            <option value='S' <?php if ($tem_ativo =='S') echo 'SELECTED'; ?>>Ativo</option>
                            <option value='N' <?php if ($tem_ativo =='N') echo 'SELECTED'; ?>>Inativo</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="tem_ativo"></label>
                        <input type="submit" value="Pesquisar" name="Pesquisar" class="btn btn-dark form-control btn_pesquisar" />
                    </div>
                    <div class="col-1">
                        <label for="tem_ativo"></label>
                        <p class="icon_print"><a href='./../pdf/gerar_pdf.php?tema=relatorioTemas' id='imprimirTemas' data-toggle='tooltip' target='_blank' title='Imprimir' class='action_icons'><i class='fas fa-print fa-2x'></i></a></p>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive table_comentario w-100">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Tema</th>            
                        <th>Data de Criação</th>
                        <th>Situação</th>            
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($resultado as $chave): ?>
                        <tr>            
                            <td class="wrap_table_content"><?= $chave["cat_descricao"] ?></td>
                            <td class="wrap_table_content"><?= $chave["tem_descricao"] ?></td>
                            <td><?= date('d/m/Y', strtotime($chave["tem_criacao"])) ?></td>
                            <td><?= $chave['tem_ativo'] == 'S' ? 'Ativo' : 'Inativo' ?></td>
                            <td class="text-center">
                                <?php
                                    $codigoTema = $chave['tem_codigo'];
                                    $codigoCategoria = $chave['cat_codigo'];
                                    $tem_desc = $chave['tem_descricao'];
                                                 
                                    if ($admin && $chave['tem_ativo'] == 'N') 
                                    {
                                        echo "<span data-toggle='modal' data-target='#ativarTema' data-tem-cod='$codigoTema'><a href='javascript:void(0)' class='action_icons' data-toggle='tooltip' title='Ativar'><span class='text-success'><i class='fas fa-check'></i></span></a></span>";  
                                    }
                                    echo "<a href='./forum_comentario.php?page=1&codigoTema=$codigoTema' id='visualizar' data-toggle='tooltip' title='Ver comentários' class='action_icons'><i class='far fa-eye'></i></a>";
                                    if ($admin)
                                    {
                                        echo "<span data-toggle='modal' data-target='#editarTema' data-cat-id='$codigoCategoria' data-tem-desc='$tem_desc' data-tem-cod='$codigoTema'><a href='javascript:void(0)' class='action_icons' data-toggle='tooltip' title='Editar'><span class='text-primary'><i class='fas fa-pen'></i></span></a></span>";  
                                        if ($chave['tem_ativo'] == 'S')
                                        {
                                            echo "<span data-toggle='modal' data-target='#desativarTema' data-tem-cod='$codigoTema'><a href='javascript:void(0)' class='action_icons' data-toggle='tooltip' title='Desativar'><span class='text-warning'><i class='fas fa-times-circle'></i></span></a></span>";  
                                        }
                                    }           
                                ?>
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
            <div class="col-sm-12">
                <span class="text-danger text-center"><?= $mensagem ?></span>
            </div>
        </div>

                    <!-- MODAIS DA TELA !-->
    </div>
            
    <?php if ($admin): ?>
        <form method="POST" id="formNovoTema">
            <div class="container">
                <div class="row">
                    <div class="col-2">
                        <input type="button" value="Novo Tema" name="Incluir" class="btn btn-dark form-contro btn_novoTema" data-toggle="modal" data-target="#novoTema"/>
                    </div>
                </div>
            </div>    
            <div class="modal fade" id="novoTema" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Novo Tema</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="txtDescricao">Descrição</label>
                                        <input type="text" id="txtDescricao" class="form-control" placeholder="Descrição" name="tem_descricao_inclusao" required="required" maxlength="300">                
                                    </div>
                                    <div class="col-6">
                                        <label class="mr-sm-2" for="cbCategoria">Categoria</label>
                                        <select class="form-control" name="cat_codigo" id="cbCategoria" required="required">
                                            <?php foreach($categorias as $categoria): ?>
                                                <option value='<?= $categoria['cat_codigo'] ?>'><?= $categoria['cat_descricao'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
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

    <form method="POST" id="formEditarTema">      
        <div class="modal fade" id="editarTema" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Alterar Tema</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <label for="txtDescricao">Descrição</label>
                                <input type="text" id="txtDescricao" class="form-control" placeholder="Descrição" name="tem_descricao_edit" required="required" maxlength="300"> 
                                <input type="hidden" id="hdnTemCodigo" name="tem_codigo_edit" />              
                            </div>
                            <div class="col-6">
                                <label class="mr-sm-2" for="cbCategoria">Categoria</label>
                                <select class="form-control" name="cat_codigo_edit" id="cbCategoria" required="required">
                                    <?php foreach($categorias as $categoria): ?>
                                        <option value='<?= $categoria['cat_codigo'] ?>'><?= $categoria['cat_descricao'] ?></option>
                                    <?php endforeach; ?>
                                 </select>
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

    <form method="POST" id="formDesativarTema">      
        <div class="modal fade" id="desativarTema" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Desativar Tema?</h4>
                        <input type="hidden" name="tem_codigo_desativar" id="hdnTemCodigoDesativar" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark form-control" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-dark form-control" name="Desativar">Sim</button>
                    </div>
                </div>
            </div>
        </div>
     </form> 
     
     <form method="POST" id="formAtivarTema">      
        <div class="modal fade" id="ativarTema" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ativar Tema?</h4>
                        <input type="hidden" name="tem_codigo_ativar" id="hdnTemCodigoAtivar" />
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

<script src="./../../assets/js/forum_home.js"></script>

</body>
</html>



