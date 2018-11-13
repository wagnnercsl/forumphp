<?php
    require_once './../cabecalho_geral.php';
    require_once './../../model/Usuario.php';
    require_once './../../database/Database.php';
    require_once './../../model/Tema.php';
    require_once './../../model/Comentario.php';
    require_once './../../model/Email.php';

    $admin = $_SESSION['administrador'];
    $mensagem = "";
    $incluir = true;
    $codigo_comentario = isset($_GET['codigoComentario']) ? $_GET['codigoComentario'] : null;
    $codigo_tema = isset($_GET['codigoTema']) ? $_GET['codigoTema'] : null;
    $limit = 5; 

    $comment = isset($_POST['comentario']) ? $_POST['comentario'] : null;

    if (isset($_POST['Enviar']))
    {
        if($comment == NULL)
        {
            $mensagem = "Para enviar um comentário é preciso escrever alguma coisa!";
            $incluir = false;
        }

        if($incluir)
        {
            $comentario = new Comentario();
            $comentario->setTem_codigo($codigo_tema);
            $comentario->setUsu_login($_SESSION['usuario']);
            $comentario->setCom_datahora(date('Y-m-d H:i:s', time()));
            $comentario->setCom_comentario($comment);
            $comentario->setCom_ativo('S');
            $comentar = $comentario->incluir();

            if (is_array($comentar))
            {
                $mensagem= "Erro: ".$comentar[0].$comentar[2];
            }
        }
    }
    
    if (isset($_POST['Desativar'])) 
    {
        $comentario = new Comentario();
        $comentario->setCom_codigo($_POST['com_codigo_desativar']);
        $resultado = $comentario->desativar();
        if (is_array($resultado)) 
        {
            $mensagem= "Erro: ".$resultado[0].$resultado[2];
        } 
    }

    $tema = Tema::listar($codigo_tema);
    $quantidade = Comentario::contar($codigo_comentario, null, 'S', $codigo_tema);
    $quantidade = ceil($quantidade / $limit);
    $start = isset($_GET['page']) ? $_GET['page'] * $limit - $limit : null;
    $resultado= Comentario::listar($codigo_comentario, null, 'S', $limit, $start, $codigo_tema);
?>

<link href="./../../assets/css/forum_home.css" rel="stylesheet" media="all">

<div class="container">
    <form method="POST" id="formComentario">
        <input type="text" style="display:none" id="codigoComentarioDesativar" name="codigoComentarioDesativar" />

     	<div class="table-responsive table_comentario">
            <table class="dados-os table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Tema</th>            
                        <th>Data de Criação</th>            
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($tema as $chave): ?>
                    <tr>            
                        <td class="wrap_table_content"><?= $chave["cat_descricao"] ?></td>
                        <td class="wrap_table_content"><?= $chave["tem_descricao"] ?></td>
                        <td><?= date('d/m/Y', strtotime($chave["tem_criacao"])) ?></td>
                        <td class="text-center">
                            <?php
                                $temaComentario = $chave['tem_codigo'];             
                                echo "<a href='./../pdf/gerar_pdf.php?temaComentario=$temaComentario' id='imprimir' data-toggle='tooltip' target='_blank' title='Imprimir'><i class='fas fa-print'></i></a>";
                            ?>
                            </td>          
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <?php foreach($resultado as $chave): ?>
            <div class="card w-100 card_comentario">
                <div class="card-img-top d-flex align-items-center bg-light" style="height: 70px;">
                    <div>
                        <img class="img-fluid img_comentario"src='<?= $chave['usu_avatar'] ?>' alt="Card image cap">
                    </div>
                    <p class="col p-2 m-0"><b><i><?= $chave['usu_login'] ?></i></b></p>
                </div>

                <div class="card-body">
                    <p class="card-text"><?= $chave['com_comentario'] ?></p>
                </div>
                <div class="card-footer text-muted">
                    <span><?= date('d/m/Y H:i', strtotime($chave["com_datahora"])) ?></span>
                    <span class="newComer text-warning text-center"><?= $mensagem ?></span>
                    <div class="icons_comentario">
                        <?php if($admin && $chave['com_ativo'] == 'S'): ?>
                            <span data-toggle="modal" data-target="#desativarComentario" data-com-cod="<?= $chave['com_codigo'] ?>"><a href="javascript:void(0)" class="desativar" data-toggle="tooltip" title="Desativar"><span class="text-warning"><i class="fas fa-times-circle"></i></span></a></span>
                        <?php elseif ($chave['usu_login'] != $_SESSION['usuario']): ?>
                            <?php
                                $codigo = $chave['com_codigo']; 
                                echo "<a href='./denunciar_comentario.php?comentario=$codigo' data-toggle='tooltip' title='Denunciar' class='action_icons'><span class='text-danger'><i class='fas fa-exclamation-triangle'></i></span></a>"; 
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="row">      
            <div class="col-sm-6">
                <ul class="pagination">
                    <?php for($i = 1; $i <= $quantidade; $i++) { 
                        echo "<li><a href='?page=$i&codigoTema=$codigo_tema' class='btn btn-dark btn_pg'>$i</a></li>";
                    } ?>
                </ul>
            </div>
        </div>    
        
        <?php if($tema[0]['tem_ativo'] == 'S'): ?>
            <div class="row form-group">
                <div class="col">
                    <label>Digite seu Comentário:</label><textarea class="form-control" rows="3" maxlength="2000" name="comentario"></textarea>
                </div>
            </div>
        <?php endif; ?>            
        <div class="row justify-content-center">
            <div class="col-3">              
                <input type="button" class="btn btn-dark form-control" value="Voltar" onclick="javascript:location.href='./home.php?page=1'" />
            </div>
            <?php if($tema[0]['tem_ativo'] == 'S'): ?>
                <div class="col-3">              
                    <input type="reset" class="btn btn-dark form-control" value="Limpar" />
                </div>
                <div class="col-3">
                    <input type="submit" class="btn btn-dark form-control" name="Enviar" value="Enviar" />
                </div>
            <?php endif; ?>              
    	</div>


    </form>
</div>

<form method="POST" id="formDesativarComentario">      
        <div class="modal fade" id="desativarComentario" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Desativar Comentário?</h4>
                        <input type="hidden" name="com_codigo_desativar" id="hdnComCodigoDesativar" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark form-control" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-dark form-control" name="Desativar">Sim</button>
                    </div>
                </div>
            </div>
        </div>
     </form>   



<?php require_once './../rodape_geral.php'; ?>

<script src="./../../assets/js/forum_comentarios.js"></script>

</body>
</html>