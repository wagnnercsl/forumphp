<?php
class Comentario
{
    private $com_codigo;
    private $tem_codigo;
    private $usu_login;
    private $com_datahora;
    private $com_comentario;
    private $com_ativo;

    public function getCom_codigo()
    {
        return $this->com_codigo;
    }

    public function setCom_codigo($com_codigo)
    {
        $this->com_codigo = $com_codigo;
    }

    public function getTem_codigo()
    {
        return $this->tem_codigo;
    }

    public function setTem_codigo($tem_codigo)
    {
        $this->tem_codigo = $tem_codigo;
    }

    public function getUsu_login()
    {
        return $this->usu_login;
    }

    public function setUsu_login($usu_login)
    {
        $this->usu_login = $usu_login;
    }
 
    public function getCom_datahora()
    {
        return $this->com_datahora;
    }

    public function setCom_datahora($com_datahora)
    {
        $this->com_datahora = $com_datahora;
    }

    public function getCom_comentario()
    {
        return $this->com_comentario;
    }

    public function setCom_comentario($com_comentario)
    {
        $this->com_comentario = $com_comentario;
    }

    public function getCom_ativo()
    {
        return $this->com_ativo;
    }

    public function setCom_ativo($com_ativo)
    {
        $this->com_ativo = $com_ativo;
    }

    public function incluir()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("INSERT INTO comentario(com_codigo, tem_codigo, usu_login, com_datahora, com_comentario, com_ativo) ".
                                                "VALUES(:com_codigo, :tem_codigo, :usu_login, :com_datahora, :com_comentario, :com_ativo) ");
        $stm->bindValue(':com_codigo', $this->getCom_codigo());
        $stm->bindValue(':tem_codigo', $this->getTem_codigo());
        $stm->bindValue(':usu_login', $this->getUsu_login());
        $stm->bindValue(':com_datahora', $this->getCom_datahora());
        $stm->bindValue(':com_comentario', $this->getCom_comentario());
        $stm->bindValue(':com_ativo', $this->getCom_ativo());
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public function alterar()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("UPDATE comentario SET ".
                                        "com_codigo=:com_codigo, ".
                                        "com_datahora=:com_datahora, ".
                                        "com_comentario=:com_comentario ".
                                         "WHERE com_codigo=:com_codigo ");
        $stm->bindValue(':com_codigo', $this->getCom_codigo());
        $stm->bindValue(':com_datahora', $this->getCom_datahora());
        $stm->bindValue(':com_comentario', $this->getCom_comentario());
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public static function listar($com_codigo=null, $com_comentario=null, $com_ativo=null, $limit=null, $start=null, $tem_codigo=null)
    {
        $conexao = Database::connect();
        $sql = "SELECT c.com_codigo, ".
                        "c.tem_codigo, ".
                        "c.usu_login, ".
                        "u.usu_avatar, ".
                        "c.com_datahora, ".
                        "c.com_comentario, ".
                        "c.com_ativo ".
                        "FROM comentario c ".
                        "JOIN tema t on t.tem_codigo = c.tem_codigo ".
                        "JOIN usuario u on u.usu_login = c.usu_login ";

        if ($com_codigo)
        {
            $sql.="WHERE com_codigo=:com_codigo ";
        }
        if ($com_comentario)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND com_comentario LIKE :com_comentario ";
            }
            else
            {
                $sql.="WHERE com_comentario LIKE :com_comentario ";
            }
        }
        if ($com_ativo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND com_ativo=:com_ativo ";
            }
            else
            {
                $sql.="WHERE com_ativo=:com_ativo ";
            }
        }
        if ($tem_codigo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND c.tem_codigo=:tem_codigo ";
            }
            else
            {
                $sql.="WHERE c.tem_codigo=:tem_codigo ";
            }

        }

        $sql.="ORDER BY c.com_datahora DESC ";

        if ($limit)
        {
            $sql.= "LIMIT :limit ";

            if ($start)
            {
                $sql.="OFFSET :start";
            }
        }
        
        $stm= $conexao->prepare($sql);

        if($com_codigo)
        {
            $stm->bindValue(':com_codigo', $com_codigo);
        }
        if($com_comentario)
        {
            $stm->bindValue(':com_comentario', '%'.$com_comentario.'%');
        }
        if($com_ativo)
        {
            $stm->bindValue(':com_ativo', $com_ativo);
        }
        if($tem_codigo)
        {
            $stm->bindValue(':tem_codigo', $tem_codigo);
        }
        if ($limit)
        {
            $stm->bindValue(':limit', (int) $limit, PDO::PARAM_INT);

            if ($start)
            {
                $stm->bindValue(':start', (int) $start, PDO::PARAM_INT);
            }
        }

        if(!$stm->execute())
            return $stm->errorInfo();

        $comentarios = array();
        while($resultado= $stm->fetch(PDO::FETCH_ASSOC)){
            $comentarios[]= array("com_codigo" => $resultado['com_codigo'],
                            "tem_codigo" => $resultado['tem_codigo'],
                            "usu_login" => $resultado['usu_login'],
                            "usu_avatar" => $resultado['usu_avatar'],
                            "com_datahora" => $resultado['com_datahora'],
                            "com_comentario" => $resultado['com_comentario'],
                            "com_ativo" => $resultado['com_ativo']);
        }
        return $comentarios;
        
    }

    public function excluir()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("DELETE FROM comentario ".
                                         "WHERE com_codigo=:com_codigo");
        $stm->bindValue(':com_codigo', $this->getCom_codigo());
        echo var_dump($stm);
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public function ativar()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("UPDATE comentario SET ".
                                        "com_ativo=:com_ativo ".
                                         "WHERE com_codigo=:com_codigo");
        $stm->bindValue(':com_codigo', $this->getCom_codigo());
        $stm->bindValue(':com_ativo', 'S');
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public function desativar()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("UPDATE comentario SET ".
                                        "com_ativo=:com_ativo ".
                                         "WHERE com_codigo=:com_codigo");
        $stm->bindValue(':com_codigo', $this->getCom_codigo());
        $stm->bindValue(':com_ativo', 'N');
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public static function contar ($com_codigo=null, $com_comentario=null, $com_ativo=null, $tem_codigo=null)
    {
        $conexao = Database::connect();
        $sql = "SELECT COUNT(*) as quantidade ".
                 "FROM comentario ";

        if ($com_codigo)
        {
            $sql.="WHERE com_codigo=:com_codigo ";
        }
        if ($com_comentario)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND com_comentario LIKE :com_comentario ";
            }
            else
            {
                $sql.="WHERE com_comentario LIKE :com_comentario ";
            }
        }
        if ($com_ativo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                 $sql.="AND com_ativo=:com_ativo ";
            }
            else
            {
                $sql.="WHERE com_ativo=:com_ativo ";
            }
        }
        if ($tem_codigo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND tem_codigo=:tem_codigo ";
            }
            else
            {
                $sql.="WHERE tem_codigo=:tem_codigo ";
            }

        }

        $stm= $conexao->prepare($sql);
                    
        if ($com_codigo)
            $stm->bindValue(':com_codigo', $com_codigo);
        if ($com_comentario)
            $stm->bindValue(':com_comentario', '%'.$com_comentario.'%');
        if ($com_ativo)
            $stm->bindValue(':com_ativo', $com_ativo);
        if($tem_codigo)
        {
            $stm->bindValue(':tem_codigo', $tem_codigo);
        }

        if (!$stm->execute())
            return $stm->errorInfo();  

        $resultado = $stm->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['quantidade'];

    }
    
}

?>