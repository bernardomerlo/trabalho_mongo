<?php

include_once '../../start/init.php';

if (!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    exit();
}

$id_barbeiro = intval($_GET['id']);

try {
    
    $barbeiro = $mongo->selectOne("barbeiros", ["id" => $id_barbeiro]);

    if (!$barbeiro) {
        header("Location: ../gerenciar_barbearia.php");
        exit();
    }

    $mongo->delete("barbeiros", ["id" => $id_barbeiro]);
    

    header("Location: ../gerenciar_barbearia.php");
    exit();
} catch (Exception $e) {
    echo "Erro ao remover barbeiro: " . $e->getMessage();
    exit();
}
