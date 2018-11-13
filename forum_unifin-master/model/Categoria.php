<?php
class Categoria
{
    private $cat_codigo;
    private $cat_descricao;
    private $cat_ativa;
    
    public function getCat_codigo()
    {
        return $this->cat_codigo;
    }
    
    public function getCat_descricao()
    {
        return $this->cat_descricao;
    }
    
    public function getCat_ativa()
    {
        return $this->cat_ativa;
    }
    
    public function setCat_codigo($cat_codigo)
    {
        $this->cat_codigo = $cat_codigo;
    }
    
    public function setCat_descricao($cat_descricao)
    {
        $this->cat_descricao = $cat_descricao;
    }
    
    public function setCat_ativa($cat_ativa)
    {
        $this->cat_ativa = $cat_ativa;
    }
    
    public function incluir()
    {
        $conexao= Database::connect();
        $stm= $conexao->prepare("INSERT INTO categoria (cat_descricao, cat_ativa) ".
                                          "VALUES (:cat_descricao,:cat_ativa)");
        $stm->bindValue(':cat_descricao', $this->getCat_descricao());
        $stm->bindValue(':cat_ativa', $this->getCat_ativa());

        if (!$stm->execute())
            return $stm->errorInfo();
    }
    
    public function alterar()
    {
        $conexao= Database::connect();
        $stm= $conexao->prepare("UPDATE categoria SET ".
                                       "cat_descricao=:cat_descricao ".
                                 "WHERE cat_codigo=:cat_codigo");
        $stm->bindValue(':cat_codigo', $this->getCat_codigo());
        $stm->bindValue(':cat_descricao', $this->getCat_descricao());
        if (!$stm->execute())
            return $stm->errorInfo();
    }
    
    public static function listar($cat_codigo=null, $cat_descricao=null, $cat_ativa=null, $limit=null, $start=null)
    {
        $conexao= Database::connect();

        $sql="SELECT cat_codigo, ".
                    "cat_descricao, ".
                    "cat_ativa ".
               "FROM categoria ";

        if ($cat_codigo)
        {
            $sql.="WHERE cat_codigo=:cat_codigo ";
        }
        if ($cat_descricao)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND cat_descricao LIKE :cat_descricao ";
            }
            else
            {
                $sql.="WHERE cat_descricao LIKE :cat_descricao ";
            }
        }
        if ($cat_ativa)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND cat_ativa=:cat_ativa ";
            }
            else
            {
                $sql.="WHERE cat_ativa=:cat_ativa ";
            }
        }

        if ($limit)
        {
            $sql.= "LIMIT :limit ";

            if ($start)
            {
                $sql.="OFFSET :start";
            }
        }

        $stm= $conexao->prepare($sql);
        
        if ($cat_codigo)
            $stm->bindValue(':cat_codigo', $cat_codigo);
        if ($cat_descricao)
            $stm->bindValue(':cat_descricao', '%'.$cat_descricao.'%');
        if ($cat_ativa)
            $stm->bindValue(':cat_ativa', $cat_ativa);
        if ($limit)
        {
            $stm->bindValue(':limit', (int) $limit, PDO::PARAM_INT);

            if ($start)
            {
                $stm->bindValue(':start', (int) $start, PDO::PARAM_INT);
            }
        }

        if (!$stm->execute())
            return $stm->errorInfo();
            
        $categorias= array();
        while ($resultado= $stm->fetch(PDO::FETCH_ASSOC))
        {
            $categorias[]= array("cat_codigo" => $resultado['cat_codigo'],
                          "cat_descricao" => $resultado['cat_descricao'],
                              "cat_ativa" => $resultado['cat_ativa']);
        }
        return $categorias;
    }

    public function ativar()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("UPDATE categoria SET ".
                                        "cat_ativa=:cat_ativa ".
                                         "WHERE cat_codigo=:cat_codigo");
        $stm->bindValue(':cat_codigo', $this->getCat_codigo());
        $stm->bindValue(':cat_ativa', 'S');
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public function desativar()
    {
        $conexao = Database::connect();
         $stm = $conexao->prepare("UPDATE categoria SET ".
                                        "cat_ativa=:cat_ativa ".
                                         "WHERE cat_codigo=:cat_codigo");
        $stm->bindValue(':cat_codigo', $this->getCat_codigo());
        $stm->bindValue(':cat_ativa', 'N');
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public static function contar ($cat_codigo=null, $cat_descricao=null, $cat_ativa=null)
    {
        $conexao = Database::connect();
        $sql = "SELECT COUNT(*) as quantidade ".
                 "FROM categoria ";

        if ($cat_codigo)
        {
            $sql.="WHERE cat_codigo=:cat_codigo ";
        }
        if ($cat_descricao)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND cat_descricao LIKE :cat_descricao ";
            }
            else
            {
                $sql.="WHERE cat_descricao LIKE :cat_descricao ";
            }
        }
        if ($cat_ativa)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                 $sql.="AND cat_ativa=:cat_ativa ";
            }
            else
            {
                $sql.="WHERE cat_ativa=:cat_ativa ";
            }
        }

        $stm= $conexao->prepare($sql);
                    
        if ($cat_codigo)
            $stm->bindValue(':cat_codigo', $cat_codigo);
        if ($cat_descricao)
            $stm->bindValue(':cat_descricao', '%'.$cat_descricao.'%');
        if ($cat_ativa)
            $stm->bindValue(':cat_ativa', $cat_ativa);

        if (!$stm->execute())
            return $stm->errorInfo();  

        $resultado = $stm->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['quantidade'];

    }
    
}
?>