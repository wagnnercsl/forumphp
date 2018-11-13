<?php
final class Database{
    private const host = "localhost";
    private const db = "db_forum";
    private const user = "root";
    private const password = "";
    
    public static function connect(){
        try
        {
            $conexao = new PDO("mysql:host=".self::host.";dbname=".self::db,self::user, self::password);
        }
        catch (PDOException $e)
        {
            die("<div>" . $e->getMessage() . "</div>");
        }
        return ($conexao);
    }
}