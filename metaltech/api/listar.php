<?php
    header('Content-Type: application/json; charset=utf8');
    require_once __DIR__.'/../config/conexao.php';

    $PDO = conexao::conectar();

    try {
        if(isset($_GET['id'])){
        $stmt = $PDO->prepare("SELECT id,nome,celular,email,data_nascimento FROM pessoa WHERE id = :id");
        $stmt->execute([':id'=>(int) $GET['id']]);
        $pessoa = $stmt->fetch();
        if ($pessoa) {
            echo json_encode(['sucesso'=>true,'dados'=>$pessoa]);
        }else {
            http_response_code(404);
            echo json_encode(['sucesso'=>false,'mensagem'=>'Registro não encontrado']);
        }
    }else {
        $stmt = $PDO->query("SELECT id,nome,celular,email,data_nascimento FROM pessoa ORDER BY nome ASC");
        $pessoas = $stmt->fetchALL();
        echo json_encode(['sucesso'=>true, 'dados'=>$pessoas]);
    }
    } catch (PDOException $e) {
        http_response_code(500);
        echo_json_encode(['sucesso'=>false, 'messagem'=>'Erro ao consultar:'.$e->getMessage()]); 
    }
?>