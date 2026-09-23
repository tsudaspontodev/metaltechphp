<?php

class conexao{
    private static $host = 'sql.freedb.tech';
    private static $dbname = 'freedb_KbdkRcKq';
    private static $user = 'u_yC8Pim';
    private static $pass = '0ANwTxkSFEit';

    private static ?PDO $instancia = null; 

    public static function conectar(): PDO {
        if (self::$instancia === null) {
            try {
                $dsn = "mysql:host=".self::$host."dbname=".self::$dbname.";charset=utf8mb4";
                self::$instancia = new PDO($dsn, self::$user, self::pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf8');
                echo json_encode(['sucesso' => false, 'mensagem'=>'Erro de conexão:'.$e->GetMessage()]);
                exit;
            }
        }

        return self::$instancia;
    }
}
?>