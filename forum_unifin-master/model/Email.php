<?php

class Email 
{
    public function emailRecuperacaoSenha($destinatario, $usu_senha)
    {
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: Forum Unifin <forum.unifin@gmail.com>';

        $titulo = "Recuperação de senha";
        $mensagem = "Prezado(a), " . "\r\n";
        $mensagem .= "Segue a sua nova senha: ". $usu_senha . "." . "\r\n";
        $mensagem .= "Ao entrar no fórum, troque sua senha imediatamente.";

        $retorno = mail($destinatario, $titulo, nl2br($mensagem), implode("\r\n", $headers));

        return $retorno;
    }

    public function denunciarComentario($com_codigo, $usu_login, $motivo)
    {
        $comentario = Comentario::listar($com_codigo);
        $admins = Usuario::listarAdmins();
        $tema = Tema::listar($comentario[0]['tem_codigo']);
        $categoria = Categoria::listar($tema[0]['cat_codigo']);

        if (sizeof($admins) == 0)
        {
            return false;
        }

        $to = $admins[0]['usu_email'];
        $cc = '';
        
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: Forum Unifin <forum.unifin@gmail.com>';

        if (sizeof($admins) > 1)
        {
            for($i = 1; $i < sizeof($admins); $i++)
            {
                $cc .= $admins[$i]['usu_email'] . ', ';
            }
        }

        if ($cc != '')
        {
            $cc = substr($cc, 0, -2);
            $headers[] = "Cc: ". $cc;
        }

        $titulo = "Comentário denunciado";
        $mensagem = "Prezado(a) Administrador(a), " . "\r\n";
        $mensagem .= "O usuário ". $usu_login. " denunciou o comentário";
        $mensagem .= " feito pelo usuário ". $comentario[0]['usu_login'] . "." . "\r\n";
        $mensagem .= "O comentário contém o seguinte conteúdo: \"". $comentario[0]['com_comentario'] . "\" e pertence ao tema ";
        $mensagem .= $tema[0]['tem_descricao'] . " da categoria " . $categoria[0]['cat_descricao'] . "." . "\r\n";
        $mensagem .= "O motivo da denúncia foi: \"" . $motivo . "\"." . "\r\n";
        $mensagem .= "http://localhost/Forum/view/forum/forum_comentario.php?page=1&codigoTema=" . $comentario[0]['tem_codigo'] . "&codigoComentario=" . $comentario[0]['com_codigo'];

        $retorno = mail($to, $titulo, nl2br($mensagem), implode("\r\n", $headers));

        return $retorno;
    }
}