<?php
class Tema
{
    private $tem_codigo;
    private $cat_codigo;
    private $tem_descricao;
    private $tem_criacao;
    private $tem_ativo;

    public function getTem_codigo()
    {
        return $this->tem_codigo;
    }

    public function getCat_codigo()
    {
        return $this->cat_codigo;
    }

    public function getTem_descricao()
    {
        return $this->tem_descricao;
    }

    public function getTem_criacao()
    {
        return $this->tem_criacao;
    }

    public function getTem_ativo()
    {
        return $this->tem_ativo;
    }

    public function setTem_codigo($tem_codigo)
    {
        $this->tem_codigo = $tem_codigo;
    }

    public function setCat_codigo($cat_codigo)
    {
        $this->cat_codigo = $cat_codigo;
    }

    public function setTem_descricao($tem_descricao)
    {
        $this->tem_descricao = $tem_descricao;
    }

    public function setTem_criacao($tem_criacao)
    {
        $this->tem_criacao = $tem_criacao;
    }

    public function setTem_ativo($tem_ativo)
    {
        $this->tem_ativo = $tem_ativo;
    }

    public function incluir()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("INSERT INTO tema(cat_codigo, tem_descricao, tem_criacao, tem_ativo) ".
                                                "VALUES(:cat_codigo, :tem_descricao, :tem_criacao, :tem_ativo) ");
        $stm->bindValue(':cat_codigo', $this->getCat_codigo());
        $stm->bindValue(':tem_descricao', $this->getTem_descricao());
        $stm->bindValue(':tem_criacao', $this->getTem_criacao());
        $stm->bindValue(':tem_ativo', $this->getTem_ativo());
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public function alterar()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("UPDATE tema SET ".
                                        "tem_codigo=:tem_codigo, ".
                                        "cat_codigo=:cat_codigo, ".
                                        "tem_descricao=:tem_descricao, ".
                                        "tem_criacao=current_timestamp ".
                                         "WHERE tem_codigo=:tem_codigo ");
        $stm->bindValue(':tem_codigo', $this->getTem_codigo());
        $stm->bindValue(':tem_descricao', $this->getTem_descricao());
        $stm->bindValue(':tem_criacao', $this->getTem_criacao());
        $stm->bindValue(':cat_codigo', $this->getCat_codigo());
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public static function listar($tem_codigo=null, $tem_descricao=null, $tem_ativo=null, $limit=null, $start=null, $cat_codigo=null)
    {
        $conexao = Database::connect();
        $sql = "SELECT t.tem_codigo, ".
                        "max(m.com_datahora) AS data_hora, ".
                        "t.cat_codigo, ".
                        "c.cat_descricao, ".
                        "t.tem_descricao, ".
                        "t.tem_criacao, ".
                        "t.tem_ativo ".
                        "FROM tema t ".
                        "INNER JOIN categoria c on c.cat_codigo = t.cat_codigo ".
                        "LEFT JOIN comentario m on m.tem_codigo = t.tem_codigo ";

        if ($tem_codigo)
        {
            $sql.="WHERE t.tem_codigo=:tem_codigo ";
        }
        if ($tem_descricao)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND tem_descricao LIKE :tem_descricao ";
            }
            else
            {
                $sql.="WHERE tem_descricao LIKE :tem_descricao ";
            }
        }
        if ($tem_ativo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND tem_ativo=:tem_ativo ";
            }
            else
            {
                $sql.="WHERE tem_ativo=:tem_ativo ";
            }
        }
        
        if ($cat_codigo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND c.cat_codigo=:cat_codigo ";
            }
            else
            {
                $sql.="WHERE c.cat_codigo=:cat_codigo ";
            }
        }

        $sql.= "GROUP BY t.tem_codigo ";
        $sql.= "ORDER BY t.tem_ativo DESC, data_hora DESC ";

        if ($limit)
        {
            $sql.= "LIMIT :limit ";

            if ($start)
            {
                $sql.="OFFSET :start ";
            }
        }

        $stm= $conexao->prepare($sql);

        if($tem_codigo)
            $stm->bindValue(':tem_codigo', $tem_codigo);
        if($tem_descricao)
            $stm->bindValue(':tem_descricao', '%'.$tem_descricao.'%');
        if($tem_ativo)
            $stm->bindValue(':tem_ativo', $tem_ativo);
        if($cat_codigo)
            $stm->bindValue(':cat_codigo', $cat_codigo);
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

        $temas = array();
        while($resultado= $stm->fetch(PDO::FETCH_ASSOC)){
            $temas[]= array("tem_codigo" => $resultado['tem_codigo'],
                            "cat_codigo" => $resultado['cat_codigo'],
                            "cat_descricao" => $resultado['cat_descricao'],
                            "tem_descricao" => $resultado['tem_descricao'],
                            "tem_criacao" => $resultado['tem_criacao'],
                            "tem_ativo" => $resultado['tem_ativo']);
        }
        return $temas;
        
    }

    public function excluir()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("DELETE tema ".
                                        "tem_codigo=:tem_codigo, ".
                                        "cat_codigo=:cat_codigo, ".
                                        "tem_descricao=:tem_descricao, ".
                                        "tem_criacao=:tem_criacao, ".
                                        "tem_ativo=:tem-ativo ".
                                         "WHERE tem_codigo=:tem_codigo");
        $stm->bindValue(':tem_codigo', $this->getTem_codigo());
        $stm->bindValue(':cat_codigo', $this->getCat_codigo());
        $stm->bindValue(':tem_descricao', $this->getTem_descricao());
        $stm->bindValue(':tem_criacao', $this->getTem_criacao());
        $stm->bindValue(':tem_ativo', $this->getTem_ativo());
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public function ativar()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("UPDATE tema SET ".
                                        "tem_ativo=:tem_ativo ".
                                         "WHERE tem_codigo=:tem_codigo");
        $stm->bindValue(':tem_codigo', $this->getTem_codigo());
        $stm->bindValue(':tem_ativo', 'S');

        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public function desativar()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("UPDATE tema SET ".
                                        "tem_ativo=:tem_ativo ".
                                         "WHERE tem_codigo=:tem_codigo");
        $stm->bindValue(':tem_codigo', $this->getTem_codigo());
        $stm->bindValue(':tem_ativo', 'N');
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public static function contar ($tem_codigo=null, $tem_descricao=null, $tem_ativo=null, $cat_codigo = null)
    {
        $conexao = Database::connect();
        $sql = "SELECT COUNT(*) as quantidade ".
                 "FROM tema ";

        if ($tem_codigo)
        {
            $sql.="WHERE tem_codigo=:tem_codigo ";
        }
        if ($tem_descricao)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND tem_descricao LIKE :tem_descricao ";
            }
            else
            {
                $sql.="WHERE tem_descricao LIKE :tem_descricao ";
            }
        }
        if ($tem_ativo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                 $sql.="AND tem_ativo=:tem_ativo ";
            }
            else
            {
                $sql.="WHERE tem_ativo=:tem_ativo ";
            }
        }

        if ($cat_codigo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND cat_codigo=:cat_codigo ";
            }
            else
            {
                $sql.="WHERE cat_codigo=:cat_codigo ";
            }
        }

        $stm= $conexao->prepare($sql);
                    
        if ($tem_codigo)
            $stm->bindValue(':tem_codigo', $tem_codigo);
        if ($tem_descricao)
            $stm->bindValue(':tem_descricao', '%'.$tem_descricao.'%');
        if ($tem_ativo)
            $stm->bindValue(':tem_ativo', $tem_ativo);
        if($cat_codigo)
            $stm->bindValue(':cat_codigo', $cat_codigo);

        if (!$stm->execute())
            return $stm->errorInfo();  

        $resultado = $stm->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['quantidade'];

    }
    
}


?>