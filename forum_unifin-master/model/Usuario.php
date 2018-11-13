<?php 
class Usuario
{
    private $usu_login;
    private $usu_nome;
    private $Usu_email;
    private $usu_senha;
    private $usu_administrador;
    private $usu_ativo;
    private $usu_avatar;
    
    public function getUsu_login()
    {
        return $this->usu_login;
    }

    public function getUsu_nome()
    {
        return $this->usu_nome;
    }

    public function getUsu_email()
    {
        return $this->Usu_email;
    }

    public function getUsu_senha()
    {
        return $this->usu_senha;
    }

    public function getUsu_administrador()
    {
        return $this->usu_administrador;
    }

    public function getUsu_ativo()
    {
        return $this->usu_ativo;
    }

    public function getUsu_avatar()
    {
        return $this->usu_avatar;
    }

    public function setUsu_login($usu_login)
    {
        $this->usu_login = $usu_login;
    }

    public function setUsu_nome($usu_nome)
    {
        $this->usu_nome = $usu_nome;
    }

    public function setUsu_email($Usu_email)
    {
        $this->Usu_email = $Usu_email;
    }

    public function setUsu_senha($usu_senha)
    {
        $this->usu_senha = $usu_senha;
    }

    public function setUsu_administrador($usu_administrador)
    {
        $this->usu_administrador = $usu_administrador;
    }

    public function setUsu_ativo($usu_ativo)
    {
        $this->usu_ativo = $usu_ativo;
    }

    public function setUsu_avatar($usu_avatar)
    {
        $this->usu_avatar = $usu_avatar;
    }

    public function incluir()
    {
        $hashed_password = password_hash($this->getUsu_senha(), PASSWORD_DEFAULT);
        $conexao = Database::connect();

        $stm = $conexao->prepare("INSERT INTO usuario (usu_login, usu_nome, usu_email, usu_senha, usu_administrador, usu_ativo, usu_avatar) ".
                                             "VALUES (:usu_login,:usu_nome,:usu_email,:usu_senha,:usu_administrador,:usu_ativo, :usu_avatar) ");
            

    	$stm->bindValue(':usu_login', $this->getUsu_login());
		$stm->bindValue(':usu_nome', $this->getUsu_nome());
		$stm->bindValue(':usu_email', $this->getUsu_email());
		$stm->bindValue(':usu_senha', $hashed_password);
		$stm->bindValue(':usu_administrador', $this->getUsu_administrador());
        $stm->bindValue(':usu_ativo', $this->getusu_ativo());
        $stm->bindValue(':usu_avatar', $this->getusu_avatar());

		if (!$stm->execute())
			return $stm->errorInfo();
    }

    public function alterar()
    {
        $conexao = Database::connect();
        
        $sql = "UPDATE usuario SET usu_nome=:usu_nome, ".
                                  "usu_email=:usu_email ";

        if ($this->getUsu_senha())
        {
            $sql.= ", usu_senha=:usu_senha ";
        }
        if ($this->getUsu_avatar())
        {
            $sql.= ", usu_avatar=:usu_avatar ";
        }
        if ($this->getUsu_administrador())
        {
            $sql.= ", usu_administrador=:usu_administrador ";
        }

        $sql.=" WHERE usu_login=:usu_login";  

        $stm = $conexao->prepare($sql);
        
        $stm->bindValue(':usu_login', $this->getUsu_login());
        $stm->bindValue(':usu_nome', $this->getUsu_nome());
        $stm->bindValue(':usu_email', $this->getUsu_email());
        if ($this->getUsu_senha())
        {
            $hashed_password = password_hash($this->getUsu_senha(), PASSWORD_DEFAULT);
            $stm->bindValue(':usu_senha', $hashed_password);
        }
        if ($this->getUsu_avatar())
        {
            $stm->bindValue(':usu_avatar', $this->getUsu_avatar());
        }
        if ($this->getUsu_administrador())
        {
            $stm->bindValue(':usu_administrador', $this->getUsu_administrador());
        }
        
        if (!$stm->execute())
            return $stm->errorInfo();

    }
    
    public function recuperarSenha()
    {
        $hashed_password = password_hash($this->getUsu_senha(), PASSWORD_DEFAULT);
        $conexao = Database::connect();
        
        $sql = "UPDATE usuario SET usu_senha=:usu_senha ".
                "WHERE usu_email=:usu_email";  

        $stm = $conexao->prepare($sql);
        
        $stm->bindValue(':usu_email', $this->getUsu_email());
        $stm->bindValue(':usu_senha', $hashed_password);

        if (!$stm->execute())
            return $stm->errorInfo();

    }
    
    public static function listar($login=null, $nome=null, $ativo=null, $limit=null, $start=null) {
        $conexao = Database::connect();
    	$sql = "SELECT usu_login, ".
    				  "usu_nome, ".                   
                      "usu_email, ".
                      "usu_administrador, ".
                      "usu_ativo ".
                 "FROM usuario ";

        if ($login)
        {
            $sql.="WHERE usu_login=:usu_login ";
        }
        if ($nome)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND usu_nome LIKE :usu_nome ";
            }
            else
            {
                $sql.="WHERE usu_nome LIKE :usu_nome ";
            }
        }
        if ($ativo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                 $sql.="AND usu_ativo=:usu_ativo ";
            }
            else
            {
                $sql.="WHERE usu_ativo=:usu_ativo ";
            }
        }

        $sql .= "ORDER BY usu_ativo DESC ";

        if ($limit)
        {
            $sql.= "LIMIT :limit ";

            if ($start)
            {
                $sql.="OFFSET :start";
            }
        }

        $stm= $conexao->prepare($sql);
                    
        if ($login)
            $stm->bindValue(':usu_login', $login);
        if ($nome)
            $stm->bindValue(':usu_nome', '%'.$nome.'%');
        if ($ativo)
            $stm->bindValue(':usu_ativo', $ativo);
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

        $usuarios = array();
        while ($resultado= $stm->fetch(PDO::FETCH_ASSOC)){
            $usuarios[]= array("usu_login" => $resultado['usu_login'],
                                "usu_nome" => $resultado['usu_nome'],
                               "usu_email" => $resultado['usu_email'],
                       "usu_administrador" => $resultado['usu_administrador'],
                               "usu_ativo" => $resultado['usu_ativo']);
        }
        return $usuarios;
    }

    public static function login($login, $password)
    {       
            $conexao = Database::connect();
            $sql = "SELECT usu_login, ".
                        "usu_nome, ".
                        "usu_email, ".
                        "usu_senha, ".
                        "usu_administrador, ".
                        "usu_ativo, ".
                        "usu_avatar ".
                    "FROM usuario ". 
                    "WHERE usu_login=:usu_login ";
                    
            $stm= $conexao->prepare($sql);

            $stm->bindValue(':usu_login', $login);
            if (!$stm->execute())
                return $stm->errorInfo();
                
            $usuarios = array();
            while ($resultado= $stm->fetch(PDO::FETCH_ASSOC)){
                $usuarios[]= array("usu_login" => $resultado['usu_login'],
                                   "usu_nome" => $resultado['usu_nome'],
                                   "usu_senha" => $resultado['usu_senha'],
                                   "usu_email" => $resultado['usu_email'],
                                   "usu_administrador" => $resultado['usu_administrador'],
                                   "usu_ativo" => $resultado['usu_ativo'],
                                   "usu_avatar" => $resultado['usu_avatar']);
                }

            if (sizeof($usuarios) > 0 && password_verify($password, $usuarios[0]['usu_senha']))
            {
                unset($usuarios[0]['usu_senha']);
            }
            else
            {
                $usuarios = array();
            }

            return $usuarios;
        
    }

    public static function contar ($login=null, $nome=null, $ativo=null)
    {
        $conexao = Database::connect();
        $sql = "SELECT COUNT(*) as quantidade ".
                 "FROM usuario ";

        if ($login)
        {
            $sql.="WHERE usu_login=:usu_login ";
        }
        if ($nome)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                $sql.="AND usu_nome LIKE :usu_nome ";
            }
            else
            {
                $sql.="WHERE usu_nome LIKE :usu_nome ";
            }
        }
        if ($ativo)
        {
            if (strpos($sql, 'WHERE') !== false)
            {
                 $sql.="AND usu_ativo=:usu_ativo ";
            }
            else
            {
                $sql.="WHERE usu_ativo=:usu_ativo ";
            }
        }

        $stm= $conexao->prepare($sql);
                    
        if ($login)
            $stm->bindValue(':usu_login', $login);
        if ($nome)
            $stm->bindValue(':usu_nome', '%'.$nome.'%');
        if ($ativo)
            $stm->bindValue(':usu_ativo', $ativo);

        if (!$stm->execute())
            return $stm->errorInfo();  

        $resultado = $stm->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['quantidade'];

    }

    public static function listarAdmins() {
        $conexao = Database::connect();
        $sql = "SELECT usu_email FROM usuario WHERE usu_administrador=:usu_administrador AND usu_ativo=:usu_ativo";
        
        $stm= $conexao->prepare($sql);
        $stm->bindValue(':usu_administrador', 'S');
        $stm->bindValue(':usu_ativo', 'S');

        if (!$stm->execute())
                return $stm->errorInfo();
                
        $admins = array();
        while ($resultado= $stm->fetch(PDO::FETCH_ASSOC)){
            $admins[]= array("usu_email" => $resultado['usu_email']);
        }

        return $admins;
    }

    public function ativar()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("UPDATE usuario SET ".
                                        "usu_ativo=:usu_ativo ".
                                         "WHERE usu_login=:usu_login");

        $stm->bindValue(':usu_login', $this->getUsu_login());
        $stm->bindValue(':usu_ativo', 'S');
        if(!$stm->execute())
            return $stm->errorInfo();
    }

    public function desativar()
    {
        $conexao = Database::connect();
        $stm = $conexao->prepare("UPDATE usuario SET ".
                                        "usu_ativo=:usu_ativo ".
                                         "WHERE usu_login=:usu_login");

        $stm->bindValue(':usu_login', $this->getUsu_login());
        $stm->bindValue(':usu_ativo', 'N');
        if(!$stm->execute())
            return $stm->errorInfo();
    }
    
}
